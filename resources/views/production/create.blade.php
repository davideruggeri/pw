@extends('layouts.dashboard')

@section('title', 'Produzione - Operazioni')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/production.css') }}">
@endpush

@section('content')
<div class="production-container" style="max-width: 42rem;">
    
    <div class="mb-8">
        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Operazioni Operative</h3>
        <p class="text-slate-500 text-sm">Inserisci i dati relativi alla produzione appena completata.</p>
    </div>

    <div class="production-card p-8 shadow-xl">
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
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Quantità Lotto (kg)</label>
                <select name="QuantitaProdotta" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl py-4 px-5 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="" disabled selected>Seleziona quantità...</option>
                    <option value="50">50 kg (Lotto Piccolo)</option>
                    <option value="100">100 kg (Lotto Standard)</option>
                    <option value="250">250 kg (Lotto Medio)</option>
                    <option value="500">500 kg (Lotto Grande)</option>
                    <option value="1000">1000 kg (Lotto Industriale)</option>
                </select>
                <p class="mt-2 text-[10px] text-slate-400 italic">Seleziona uno dei formati di produzione standard.</p>
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
