@extends('layouts.dashboard')

@section('title', 'Funzionalità in Arrivo')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] animate-fade-in">
    <div class="w-24 h-24 bg-indigo-100 dark:bg-indigo-900/30 rounded-3xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
    </div>
    
    <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter mb-4">Work in Progress</h3>
    <p class="text-slate-500 text-lg text-center max-w-md">
        La sezione <span class="text-indigo-600 dark:text-indigo-400 font-bold">"{{ $feature }}"</span> è attualmente in fase di sviluppo per il modulo avanzato dell'ERP.
    </p>
    
    <div class="mt-12">
        <a href="{{ url()->previous() }}" class="px-8 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all">
            Torna Indietro
        </a>
    </div>
</div>
@endsection
