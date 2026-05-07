@extends('layouts.dashboard')

@section('title', 'Funzionalità in Arrivo')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] animate-fade-in text-center">
    <div class="w-24 h-24 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mb-8 shadow-xl shadow-indigo-100/50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
    </div>
    
    <h3 class="text-3xl font-black text-slate-800 mb-4 tracking-tighter">Work in Progress</h3>
    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">
        Stiamo lavorando per rendere operativa la sezione <span class="text-indigo-600 font-bold uppercase tracking-widest text-xs px-2 py-1 bg-indigo-50 rounded-lg ml-1">{{ $feature }}</span>. 
        Torna a trovarci presto per scoprire le nuove funzionalità del Gestionale.
    </p>

    <div class="mt-12 flex gap-4">
        <a href="{{ url()->previous() }}" class="px-8 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-50 transition shadow-sm">
            Torna Indietro
        </a>
        <a href="{{ route('home') }}" class="btn-premium px-8 py-3 shadow-lg shadow-indigo-100">
            Torna alla Dashboard
        </a>
    </div>
</div>
@endsection
