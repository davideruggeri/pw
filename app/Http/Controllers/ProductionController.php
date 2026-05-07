<?php

namespace App\Http\Controllers;

use App\Models\ProduzioneLog;
use App\Models\Prodotto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    public function index()
    {
        // Solo visione generale (Overview)
        $latestLogs = ProduzioneLog::with('prodotto')
            ->orderBy('DataProduzione', 'desc')
            ->limit(5)
            ->get();

        $totalProducedMonth = ProduzioneLog::whereMonth('DataProduzione', now()->month)
            ->sum('QuantitaProdotta');

        return view('production.index', compact('latestLogs', 'totalProducedMonth'));
    }

    public function create()
    {
        $prodotti = Prodotto::all();
        return view('production.create', compact('prodotti'));
    }

    public function history()
    {
        $logs = ProduzioneLog::with('prodotto', 'responsabile')
            ->orderBy('DataProduzione', 'desc')
            ->paginate(15);

        return view('production.history', compact('logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'CodiceUnivoco_FK' => 'required|exists:prodotto,CodiceUnivoco',
            'QuantitaProdotta' => 'required|numeric|min:1',
        ]);

        ProduzioneLog::create([
            'CodiceUnivoco_FK' => $request->CodiceUnivoco_FK,
            'QuantitaProdotta' => $request->QuantitaProdotta,
            'Matricola_FK' => Auth::user()->matricola_fk,
            'DataProduzione' => now(),
            'CostoEnergiaStimato' => $request->QuantitaProdotta * 0.15,
        ]);

        return redirect()->route('production.index')->with('success', 'Produzione registrata con successo.');
    }
}
