@extends('layouts.dashboard')

@section('title', 'Manutenzione - Operazioni')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/maintenance.css') }}">
@endpush

@section('content')
<div class="maintenance-container" style="max-width: 42rem;">
    
    <div class="mb-8">
        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Operazioni di Manutenzione</h3>
        <p class="text-slate-500 text-sm">Registra i dettagli dell'attività tecnica svolta sugli impianti.</p>
    </div>

    <div class="maintenance-card p-8 shadow-xl">
        <form action="{{ route('maintenance.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tipo Intervento</label>
                    <select name="TipoIntervento" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="Preventiva">Preventiva (Ordinaria)</option>
                        <option value="Straordinaria">Straordinaria (Riparazione)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ore Fermo Macchina</label>
                    <input type="number" step="0.5" name="OreFermoMacchina" placeholder="es. 2.5" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Costo Ricambi (Euro)</label>
                <input type="number" step="0.01" name="CostoRicambi" placeholder="0.00" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Descrizione Intervento</label>
                <textarea name="NoteIntervento" rows="3" placeholder="Descrivi brevemente l'attività svolta..." required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                    Salva Intervento
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
