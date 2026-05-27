<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Repositories\Interfaces\ProdottoRepositoryInterface;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    protected $orderService;
    protected $prodottoRepo;

    public function __construct(
        OrderService $orderService,
        ProdottoRepositoryInterface $prodottoRepo
    ) {
        $this->orderService = $orderService;
        $this->prodottoRepo = $prodottoRepo;
    }

    public function index(Request $request)
    {
        $query = \App\Models\OrdineVendita::with(['cliente', 'venditore', 'dettagliVendita.prodotto']);

        if ($request->filled('search')) {
            $query->where('IDOrdineVendita', 'like', '%' . $request->search . '%')
                  ->orWhereHas('cliente', function($q) use ($request) {
                      $q->where('Nome', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('Stato', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $orders = $query->orderBy('Data', 'desc')->paginate($perPage);

        // Stats for the archive
        $stats = [
            'total_today' => \App\Models\OrdineVendita::whereDate('Data', \Carbon\Carbon::today())->count(),
            'total_week' => \App\Models\OrdineVendita::whereBetween('Data', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->count(),
            'pending' => \App\Models\OrdineVendita::where('Stato', 'Inviato')->count(),
        ];

        return view('sales.orders.index', compact('orders', 'perPage', 'stats'));
    }

    public function pending(Request $request)
    {
        $query = \App\Models\OrdineVendita::with(['cliente', 'venditore', 'dettagliVendita.prodotto'])
            ->where('Stato', 'In Attesa');

        // Ricerca per ID o Nome Cliente
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('IDOrdineVendita', 'like', '%' . $request->search . '%')
                  ->orWhereHas('cliente', function($c) use ($request) {
                      $c->where('Nome', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->get();

        // Ordinamento in PHP
        $sort = $request->input('sort', 'date_asc');
        switch ($sort) {
            case 'date_desc':
                $orders = $orders->sortByDesc('Data');
                break;
            case 'value_asc':
                $orders = $orders->sortBy('totale_ordine');
                break;
            case 'value_desc':
                $orders = $orders->sortByDesc('totale_ordine');
                break;
            case 'date_asc':
            default:
                $orders = $orders->sortBy('Data');
                break;
        }

        // Calcolo metriche per KPI (globali)
        $allPending = \App\Models\OrdineVendita::with(['dettagliVendita'])
            ->where('Stato', 'In Attesa')
            ->get();

        $totalPendingCount = $allPending->count();
        $totalPendingValue = $allPending->sum('totale_ordine');
        $maxPendingValue = $allPending->isNotEmpty() ? $allPending->max('totale_ordine') : 0;

        return view('sales.orders.pending', compact(
            'orders',
            'totalPendingCount',
            'totalPendingValue',
            'maxPendingValue'
        ));
    }

    public function approve($id)
    {
        $ordine = \App\Models\OrdineVendita::with('dettagliVendita.prodotto')->findOrFail($id);
        
        if ($ordine->Stato !== 'In Attesa') {
            return back()->with('error', 'Questo ordine è già stato elaborato.');
        }

        // 1. Verifica scorte sufficienti per tutti i prodotti nell'ordine
        foreach ($ordine->dettagliVendita as $dettaglio) {
            $prodotto = $dettaglio->prodotto;
            if ($prodotto && $prodotto->Giacenza < $dettaglio->QuantitaRichiesta) {
                return back()->with('error', "Impossibile approvare l'ordine #{$id}: scorte insufficienti per il prodotto '{$prodotto->NomeProdotto}' (Giacenza: {$prodotto->Giacenza}, Richiesto: {$dettaglio->QuantitaRichiesta}).");
            }
        }

        // 2. Sottrai i prodotti dal magazzino, registra il movimento e invia notifiche se necessario
        foreach ($ordine->dettagliVendita as $dettaglio) {
            $prodotto = $dettaglio->prodotto;
            if ($prodotto) {
                $prodotto->Giacenza -= $dettaglio->QuantitaRichiesta;
                $prodotto->save();

                // Registra il movimento di scarico
                \App\Models\MovimentoMagazzino::create([
                    'CodiceUnivoco_FK' => $prodotto->CodiceUnivoco,
                    'Quantita' => $dettaglio->QuantitaRichiesta,
                    'Tipo' => 'scarico',
                    'DataMovimento' => now(),
                    'CostoTotale' => 0
                ]);

                // Genera una notifica se il prodotto è sceso sotto scorta
                if ($prodotto->Giacenza < $prodotto->ScortaMinima) {
                    $staffUsers = \App\Models\User::whereIn('role', ['admin', 'logistics', 'production'])->get();
                    \Illuminate\Support\Facades\Notification::send($staffUsers, new \App\Notifications\LowStockAlertNotification($prodotto));
                }
            }
        }

        $ordine->update(['Stato' => 'Approvato']);

        return redirect()->route('orders.pending')->with('success', "Ordine #{$id} approvato con successo!");
    }

    public function reject($id)
    {
        $ordine = \App\Models\OrdineVendita::findOrFail($id);
        
        if ($ordine->Stato !== 'In Attesa') {
            return back()->with('error', 'Questo ordine è già stato elaborato.');
        }

        $ordine->update(['Stato' => 'Annullato']);

        return redirect()->route('orders.pending')->with('success', "Ordine #{$id} rifiutato correttamente.");
    }

    public function ship($id)
    {
        $ordine = \App\Models\OrdineVendita::findOrFail($id);
        
        if ($ordine->Stato === 'Spedito') {
            return back()->with('error', 'Questo ordine è già stato spedito.');
        }

        $ordine->update(['Stato' => 'Spedito']);

        return back()->with('success', "Ordine #{$id} confermato come spedito!");
    }

    public function create()
    {
        $clienti = Cliente::orderBy('Nome')->get();
        $prodotti = $this->prodottoRepo->all();
        
        return view('sales.orders.create', compact('clienti', 'prodotti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'CodiceCliente_FK' => 'required|exists:cliente,CodiceCliente',
            'prodotti' => 'required|array|min:1',
            'prodotti.*.CodiceUnivoco' => 'required|exists:prodotto,CodiceUnivoco',
            'prodotti.*.Quantita' => 'required|integer|min:1',
        ]);

        try {
            $matricola = auth()->user()->dipendente?->Matricola ?? 2001;
            $this->orderService->createOrder($request->all(), $matricola);

            return redirect()->route('sales.dashboard')->with('success', 'Ordine creato e magazzino aggiornato!');

        } catch (Throwable $e) {
            return back()->withErrors(['error' => 'Errore: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $ordine = \App\Models\OrdineVendita::with(['cliente', 'dettagliVendita.prodotto', 'venditore'])->findOrFail($id);
        return view('admin.orders.show', compact('ordine'));
    }
}
