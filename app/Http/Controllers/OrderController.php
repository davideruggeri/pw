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

    public function pending()
    {
        $orders = \App\Models\OrdineVendita::with(['cliente', 'venditore', 'dettagliVendita.prodotto'])
            ->where('Stato', 'Inviato')
            ->orderBy('Data', 'asc')
            ->get();

        return view('sales.orders.pending', compact('orders'));
    }

    public function approve($id)
    {
        $ordine = \App\Models\OrdineVendita::findOrFail($id);
        
        if ($ordine->Stato !== 'Inviato') {
            return back()->with('error', 'Questo ordine è già stato elaborato.');
        }

        $ordine->update(['Stato' => 'Completato']);

        return redirect()->route('orders.pending')->with('success', "Ordine #{$id} approvato con successo!");
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
