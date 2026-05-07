<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    public function index()
    {
        $prodotti = Prodotto::with('categoria')
            ->orderBy('QuantitaGiacenza', 'asc')
            ->paginate(15);
        
        $totalWarehouseValue = Prodotto::all()->sum(fn($p) => $p->QuantitaGiacenza * $p->CostoProduzione);
        $lowStockCount = Prodotto::whereRaw('QuantitaGiacenza <= ScortaMinima')->count();

        return view('logistics.index', compact('prodotti', 'totalWarehouseValue', 'lowStockCount'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'CodiceUnivoco' => 'required|exists:prodotto,CodiceUnivoco',
            'QuantitaGiacenza' => 'required|numeric|min:0',
        ]);

        $prodotto = Prodotto::where('CodiceUnivoco', $request->CodiceUnivoco)->firstOrFail();
        $prodotto->update([
            'QuantitaGiacenza' => $request->QuantitaGiacenza
        ]);

        return redirect()->route('logistics.index')->with('success', "Giacenza aggiornata per {$prodotto->Descrizione}.");
    }
}
