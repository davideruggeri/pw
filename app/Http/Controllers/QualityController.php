<?php

namespace App\Http\Controllers;

use App\Models\QualitaLog;
use App\Models\ProduzioneLog;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    public function index()
    {
        $latestLogs = QualitaLog::with('produzione.prodotto')
            ->orderBy('DataControllo', 'desc')
            ->limit(5)
            ->get();
        
        $totalProduced = ProduzioneLog::whereMonth('DataProduzione', now()->month)->sum('QuantitaProdotta');
        $totalRejected = QualitaLog::whereMonth('DataControllo', now()->month)->sum('QuantitaScartata');
        
        $rejectionRate = $totalProduced > 0 ? ($totalRejected / $totalProduced) * 100 : 0;

        return view('quality.index', compact('latestLogs', 'rejectionRate', 'totalRejected'));
    }

    public function create()
    {
        $recentBatches = ProduzioneLog::with('prodotto')
            ->whereDoesntHave('qualita')
            ->orderBy('DataProduzione', 'desc')
            ->limit(20)
            ->get();

        return view('quality.create', compact('recentBatches'));
    }

    public function history()
    {
        $logs = QualitaLog::with('produzione.prodotto')
            ->orderBy('DataControllo', 'desc')
            ->paginate(15);
            
        return view('quality.history', compact('logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'IDLogProduzione_FK' => 'required|exists:produzione_log,IDLogProduzione',
            'Esito' => 'required|in:PASS,FAIL',
            'QuantitaScartata' => 'required|numeric|min:0',
            'NoteDifetto' => 'nullable|string|max:500',
        ]);

        QualitaLog::create([
            'IDLogProduzione_FK' => $request->IDLogProduzione_FK,
            'Esito' => $request->Esito,
            'QuantitaScartata' => $request->QuantitaScartata,
            'NoteDifetto' => $request->NoteDifetto,
            'DataControllo' => now(),
        ]);

        return redirect()->route('quality.index')->with('success', 'Controllo qualità registrato con successo.');
    }
}
