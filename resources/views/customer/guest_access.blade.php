@extends('layouts.dashboard')

@section('title', 'Accesso Limitato')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/guest-access.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-20 px-6 animate-fade-in">
    <div class="guest-access-card">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-pink-500/5 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="icon-box-large">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="guest-title">Accedi per continuare</h2>
            <p class="guest-description">
                Questa sezione è riservata ai clienti registrati. Accedi o crea un account gratuito per visualizzare i tuoi ordini, i prodotti preferiti e gestire il tuo carrello.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('login.customer') }}" class="w-full sm:w-auto px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black hover:bg-indigo-700 transition shadow-lg">
                    Accedi ora
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-slate-800 transition shadow-lg">
                    Registrati
                </a>
            </div>
            
            <a href="{{ route('customer.dashboard') }}" class="mt-12 inline-block text-slate-400 font-bold hover:text-indigo-600 transition">
                ← Torna alla Dashboard Pubblica
            </a>
        </div>
    </div>
</div>
@endsection
