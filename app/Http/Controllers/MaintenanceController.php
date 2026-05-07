<?php

namespace App\Http\Controllers;

use App\Models\ManutenzioneLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index()
    {
        $latestLogs = ManutenzioneLog::with('tecnico')
            ->orderBy('DataIntervento', 'desc')
            ->limit(5)
            ->get();
        
        $totalDowntimeMonth = ManutenzioneLog::whereMonth('DataIntervento', now()->month)
            ->sum('OreFermoMacchina');

        $totalCostMonth = ManutenzioneLog::whereMonth('DataIntervento', now()->month)
            ->sum('CostoRicambi');

        return view('maintenance.index', compact('latestLogs', 'totalDowntimeMonth', 'totalCostMonth'));
    }

    public function create()
    {
        return view('maintenance.create');
    }

    public function history()
    {
        $logs = ManutenzioneLog::with('tecnico')
            ->orderBy('DataIntervento', 'desc')
            ->paginate(15);
            
        return view('maintenance.history', compact('logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TipoIntervento' => 'required|in:Preventiva,Straordinaria',
            'OreFermoMacchina' => 'required|numeric|min:0.5',
            'CostoRicambi' => 'nullable|numeric|min:0',
            'NoteIntervento' => 'required|string|max:500',
        ]);

        ManutenzioneLog::create([
            'Matricola_FK' => Auth::user()->matricola_fk,
            'DataIntervento' => now(),
            'TipoIntervento' => $request->TipoIntervento,
            'OreFermoMacchina' => $request->OreFermoMacchina,
            'CostoRicambi' => $request->CostoRicambi ?? 0,
            'NoteIntervento' => $request->NoteIntervento,
        ]);

        return redirect()->route('maintenance.index')->with('success', 'Intervento di manutenzione registrato.');
    }
}
