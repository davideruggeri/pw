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
