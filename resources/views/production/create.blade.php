@extends('layouts.dashboard')

@section('title', 'Registra Produzione')

@section('content')
<div class="max-w-2xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-8">
        <a href="{{ route('production.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Torna alla Dashboard
        </a>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Nuovo Lotto di Produzione</h3>
        <p class="text-slate-500 text-sm">Inserisci i dati relativi alla produzione appena completata.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-xl">
        <form action="{{ route('production.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prodotto Realizzato</label>
                <select name="CodiceUnivoco_FK" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="" disabled selected>Seleziona il prodotto...</option>
                    @foreach($prodotti as $p)
                        <option value="{{ $p->CodiceUnivoco }}">{{ $p->Descrizione }} (Cod: {{ $p->CodiceUnivoco }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Quantità Prodotta (kg)</label>
                <input type="number" name="QuantitaProdotta" placeholder="es. 500" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                    Salva e Registra Lotto
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 p-6 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl">
        <div class="flex gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <p class="text-sm font-bold text-amber-800 dark:text-amber-400">Nota Automatica</p>
                <p class="text-xs text-amber-700/70 dark:text-amber-400/60">Il costo energetico verrà calcolato automaticamente in base alla quantità inserita.</p>
            </div>
        </div>
    </div>

</div>
@endsection
