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
                                    ->orWhere('QuantitaGiacenza', '<', 50)
                                    ->count();
        
        $recentUpdates = Prodotto::orderBy('CodiceUnivoco', 'desc')->take(3)->get();

        return view('logistics.index', compact('totalStockValue', 'lowStockCount', 'recentUpdates'));
    }

    public function replenishment(Request $request)
    {
        $query = Prodotto::query();

        if ($request->has('filter') && $request->filter == 'low_stock') {
            $query->whereColumn('QuantitaGiacenza', '<', 'ScortaMinima')
                  ->orWhere('QuantitaGiacenza', '<', 50);
        }

        if ($request->filled('search')) {
            $query->where('Descrizione', 'like', '%' . $request->search . '%')
                  ->orWhere('CodiceUnivoco', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(15);
        
        return view('logistics.replenishment', compact('products'));
    }

    public function inventory(Request $request)
    {
        $query = Prodotto::query();

        if ($request->filled('search')) {
            $query->where('NomeProdotto', 'like', '%' . $request->search . '%')
                  ->orWhere('CodiceUnivoco', 'like', '%' . $request->search . '%')
                  ->orWhere('Descrizione', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage);

        return view('logistics.inventory', compact('products', 'perPage'));
    }

    public function updateForm()
    {
        $products = Prodotto::with('categoria')->get();
        $categories = \App\Models\Categoria::all();
        return view('logistics.update', compact('products', 'categories'));
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

        return redirect()->route('logistics.index')->with('success', $msg);
    }
}
