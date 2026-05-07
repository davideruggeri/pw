@extends('layouts.app')

@section('title', 'Benvenuti nel Sistema Gestionale')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/prelobby.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="lobby-wrapper">
        <div class="lobby-header animate-fade-down">
            <h1 class="glow-text">Sistema Gestionale Aziendale</h1>
            <p class="subtitle">Scegli come accedere ai nostri servizi</p>
        </div>

        <div class="lobby-grid">
            <!-- Area Cliente -->
            <div class="lobby-card glass animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex-1 flex flex-col items-center">
                    <div class="h-[60px] flex items-center justify-center text-4xl mb-4">🛍️</div>
                    <h2 class="text-xl font-bold text-white mb-2">Area Clienti</h2>
                    <p class="text-slate-400 text-sm leading-relaxed mb-4">Accedi per consultare il catalogo prodotti, gestire i tuoi ordini e i tuoi preferiti.</p>
                </div>
                <div class="flex flex-col gap-4 mt-auto">
                    <div class="flex flex-col gap-2 min-h-[130px] justify-center">
                        <a href="{{ route('login.customer') }}" class="btn-lobby primary">Accedi</a>
                        <div class="divider"><span>oppure</span></div>
                        <a href="{{ route('register') }}" class="btn-lobby secondary">Registrati Ora</a>
                    </div>
                </div>

            </div>

            <!-- Area Dipendenti -->
            <div class="lobby-card glass animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex-1 flex flex-col items-center">
                    <div class="h-[60px] flex items-center justify-center text-4xl mb-4">🏢</div>
                    <h2 class="text-xl font-bold text-white mb-2">Area Dipendenti</h2>
                    <p class="text-slate-400 text-sm leading-relaxed mb-4">Accesso riservato ai dipendenti per la gestione
                        della produzione e logistica.</p>
                </div>
                <div class="flex flex-col gap-4 mt-auto">
                    <div class="flex flex-col gap-2 min-h-[130px] justify-start">
                        <a href="{{ route('login.staff') }}" class="btn-lobby accent">Accesso Staff</a>
                    </div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Richiede Matricola o Email
                        aziendale</p>
                </div>
            </div>
        </div>

        <!-- Statistiche Aziendali -->
        <div class="lobby-stats animate-fade-in" style="animation-delay: 0.4s">
            <div class="text-center flex-1">
                <span class="block text-2xl md:text-3xl font-black text-white">{{ $stats['clienti'] }}</span>
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Clienti Attivi</span>
            </div>
            <div class="text-center flex-1">
                <span class="block text-2xl md:text-3xl font-black text-white">{{ $stats['staff'] }}</span>
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Personale Staff</span>
            </div>
            <div class="text-center flex-1">
                <span class="block text-2xl md:text-3xl font-black text-white">{{ $stats['reparti'] }}</span>
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Reparti Aziendali</span>
            </div>
        </div>
    </div>
@endsection