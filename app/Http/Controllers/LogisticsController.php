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
        $lowStockCount = $products->where('Giacenza', '<', 500)->count();
        $recentUpdates = Prodotto::orderBy('updated_at', 'desc')->take(5)->get();

        return view('logistics.index', compact('totalStockValue', 'lowStockCount', 'recentUpdates'));
    }

    public function inventory()
    {
        $products = Prodotto::paginate(10);
        return view('logistics.inventory', compact('products'));
    }

    public function updateForm()
    {
        $products = Prodotto::all();
        return view('logistics.update', compact('products'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'IDProdotto_FK' => 'required|exists:prodotto,CodiceUnivoco',
            'Quantita' => 'required|numeric',
            'Tipo' => 'required|in:carico,scarico'
        ]);

        $prodotto = Prodotto::where('CodiceUnivoco', $request->IDProdotto_FK)->first();
        
        if ($request->Tipo === 'scarico' && $prodotto->Giacenza < $request->Quantita) {
            return back()->with('error', 'Giacenza insufficiente per lo scarico.');
        }

        if ($request->Tipo === 'carico') {
            $prodotto->Giacenza += $request->Quantita;
        } else {
            $prodotto->Giacenza -= $request->Quantita;
        }

        $prodotto->save();

        return redirect()->route('logistics.index')->with('success', 'Magazzino aggiornato con successo.');
    }
}
