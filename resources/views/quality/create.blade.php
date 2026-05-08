@extends('layouts.dashboard')

@section('title', 'Qualità - Operazioni')

@section('content')
<div class="max-w-2xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-8">

        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Operazioni di Controllo</h3>
        <p class="text-slate-500 text-sm">Verifica la conformità dei lotti prodotti recentemente.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-xl">
        <form action="{{ route('quality.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Lotto da controllare</label>
                <select name="IDLogProduzione_FK" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="" disabled selected>Seleziona un lotto...</option>
                    @foreach($recentBatches as $batch)
                        <option value="{{ $batch->IDLogProduzione }}">Lotto #{{ $batch->IDLogProduzione }} - {{ $batch->prodotto->Descrizione }} ({{ $batch->QuantitaProdotta }}kg)</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Esito Controllo</label>
                    <select name="Esito" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="PASS">PASS (Approvato)</option>
                        <option value="FAIL">FAIL (Scartato)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Quantità Scartata (kg)</label>
                    <input type="number" name="QuantitaScartata" placeholder="0" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Note Difetto (se FAIL)</label>
                <textarea name="NoteDifetto" rows="3" placeholder="Descrivi eventuali difetti riscontrati..." class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                    Salva Controllo Qualità
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
