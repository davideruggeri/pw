@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="landing-wrapper">
    <!-- Background Decor -->
    <div class="landing-glow-1"></div>
    <div class="landing-glow-2"></div>

    <div class="landing-content animate-fade-in">
        <h1 class="landing-title">Gestionale</h1>
        <p class="landing-subtitle">
            La piattaforma integrata per la gestione intelligente di inventario, vendite e risorse umane.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ url('/admin/dashboard') }}" class="btn-premium px-10 py-5 text-lg shadow-2xl">
                    Vai alla Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-premium px-10 py-5 text-lg shadow-2xl">
                    Accedi al Sistema
                </a>
            @endauth
        </div>

        <div class="landing-stats-grid">
            <div class="text-center">
                <p class="landing-stat-value">{{ $stats['clienti'] }}</p>
                <p class="landing-stat-label">Clienti Attivi</p>
            </div>
            <div class="text-center">
                <p class="landing-stat-value">{{ $stats['staff'] }}</p>
                <p class="landing-stat-label">Personale Staff</p>
            </div>
            <div class="text-center">
                <p class="landing-stat-value">{{ $stats['reparti'] }}</p>
                <p class="landing-stat-label">Reparti Aziendali</p>
            </div>
        </div>
    </div>
</div>
@endsection