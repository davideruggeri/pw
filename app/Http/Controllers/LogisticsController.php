<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodotto;

class LogisticsController extends Controller
{
    public function index()
    {
        $products = Prodotto::all();
        $totalStockValue = $products->sum(fn($p) => $p->Giacenza * ($p->PrezzoListino ?? 0));
        
        $lowStockCount = Prodotto::whereColumn('QuantitaGiacenza', '<', 'ScortaMinima')
                                    ->count();
        
        $recentUpdates = Prodotto::orderBy('CodiceUnivoco', 'desc')->take(3)->get();

        return view('logistics.index', compact('totalStockValue', 'lowStockCount', 'recentUpdates'));
    }

    public function replenishment(Request $request)
    {
        $query = Prodotto::query();

        if ($request->has('filter') && $request->filter == 'low_stock') {
            $query->whereColumn('QuantitaGiacenza', '<', 'ScortaMinima');
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('Descrizione', 'like', '%' . $request->search . '%')
                  ->orWhere('CodiceUnivoco', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->input('per_page', 15);
        $products = $query->paginate($perPage);

        // Calcolo metriche per KPI
        $allProducts = Prodotto::all();
        $totalProductsCount = $allProducts->count();
        $lowStockCount = Prodotto::whereColumn('QuantitaGiacenza', '<', 'ScortaMinima')->count();

        return view('logistics.replenishment', compact(
            'products', 
            'perPage', 
            'totalProductsCount', 
            'lowStockCount'
        ));
    }

    public function inventory(Request $request)
    {
        $query = Prodotto::query();

        // Filtro per stato scorte
        if ($request->has('filter') && $request->filter == 'low_stock') {
            $query->whereColumn('QuantitaGiacenza', '<', 'ScortaMinima');
        }

        // Filtro per categoria
        if ($request->filled('category')) {
            $query->where('IDCategoria_FK', $request->category);
        }

        // Ricerca testuale
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('Descrizione', 'like', '%' . $request->search . '%')
                  ->orWhere('CodiceUnivoco', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage);

        // Calcolo metriche globali per KPI
        $allProducts = Prodotto::all();
        $totalProductsCount = $allProducts->count();
        $totalStockValue = $allProducts->sum(fn($p) => $p->Giacenza * ($p->PrezzoListino ?? 0));
        $lowStockCount = Prodotto::whereColumn('QuantitaGiacenza', '<', 'ScortaMinima')
                                    ->count();

        $categories = \App\Models\Categoria::all();

        return view('logistics.inventory', compact(
            'products', 
            'perPage', 
            'totalProductsCount', 
            'totalStockValue', 
            'lowStockCount',
            'categories'
        ));
    }

    public function updateForm()
    {
        $products = Prodotto::with('categoria')->get();
        $categories = \App\Models\Categoria::all();

        // Calcolo metriche per KPI
        $totalProductsCount = $products->count();
        $lowStockCount = Prodotto::whereColumn('QuantitaGiacenza', '<', 'ScortaMinima')
                                    ->count();
        $todayMovementsCount = \App\Models\MovimentoMagazzino::whereDate('DataMovimento', \Carbon\Carbon::today())->count();

        return view('logistics.update', compact(
            'products', 
            'categories', 
            'totalProductsCount', 
            'lowStockCount', 
            'todayMovementsCount'
        ));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'IDProdotto_FK' => 'required|exists:prodotto,CodiceUnivoco',
            'Quantita' => 'required|numeric|min:0.01',
            'Tipo' => 'required|in:carico,scarico'
        ]);

        $prodotto = Prodotto::where('CodiceUnivoco', $request->IDProdotto_FK)->first();
        
        if ($request->Tipo === 'scarico' && $prodotto->Giacenza < $request->Quantita) {
            return back()->with('error', 'Giacenza insufficiente per lo scarico.');
        }

        // Calcolo costo (se carico è un rifornimento, quindi una spesa)
        $costoTotale = 0;
        if ($request->Tipo === 'carico') {
            $costoTotale = $request->Quantita * ($prodotto->CostoProduzione ?? 0);
            $prodotto->Giacenza += $request->Quantita;
        } else {
            $prodotto->Giacenza -= $request->Quantita;
        }

        $prodotto->save();

        // Registro il movimento
        \App\Models\MovimentoMagazzino::create([
            'CodiceUnivoco_FK' => $prodotto->CodiceUnivoco,
            'Quantita' => $request->Quantita,
            'Tipo' => $request->Tipo,
            'CostoTotale' => $costoTotale,
            'DataMovimento' => now()
        ]);

        $msg = $request->Tipo === 'carico' 
            ? "Carico effettuato: aggiunti " . $request->Quantita . " unità. Registrata spesa di € " . number_format($costoTotale, 2)
            : "Scarico effettuato con successo.";

        return redirect()->route('inventory.index')->with('success', $msg);
    }

    public function replenishmentHistory(Request $request)
    {
        $query = \App\Models\MovimentoMagazzino::with('prodotto')->where('Tipo', 'carico');

        if ($request->filled('search')) {
            $query->whereHas('prodotto', function($q) use ($request) {
                $q->where('Descrizione', 'like', '%' . $request->search . '%')
                  ->orWhere('CodiceUnivoco', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->input('per_page', 15);
        $movements = $query->orderBy('DataMovimento', 'desc')->paginate($perPage);

        // Calcoliamo KPI/metriche per lo storico rifornimenti
        $totalReplenishedQuantity = \App\Models\MovimentoMagazzino::where('Tipo', 'carico')->sum('Quantita');
        $totalReplenishmentCost = \App\Models\MovimentoMagazzino::where('Tipo', 'carico')->sum('CostoTotale');

        return view('logistics.history', compact('movements', 'perPage', 'totalReplenishedQuantity', 'totalReplenishmentCost'));
    }
}
