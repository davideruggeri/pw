@extends('layouts.dashboard')

@section('title', 'Registra Intervento Manutenzione')

@section('content')
<div class="max-w-2xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-8">
        <a href="{{ route('maintenance.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Torna alla Dashboard
        </a>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Nuovo Intervento</h3>
        <p class="text-slate-500 text-sm">Registra i dettagli dell'attività tecnica svolta sugli impianti.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-xl">
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
