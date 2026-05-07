@extends('layouts.dashboard')

@section('title', 'Commerciale - Overview')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="admin-kpi-grid animate-fade-in">
    <!-- KPI Card: Vendite -->
    <div class="kpi-card shadow-sm">
        <p class="kpi-label">Fatturato Mese Corrente</p>
        <h3 class="kpi-value">€ {{ number_format($monthlyRevenue, 2, ',', '.') }}</h3>
        <p class="text-xs text-emerald-500 font-bold mt-2 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
            </svg>
            In crescita
        </p>
    </div>

    <!-- KPI Card: Clienti -->
    <div class="kpi-card shadow-sm">
        <p class="kpi-label">Nuovi Clienti</p>
        <h3 class="kpi-value">{{ $newClients }}</h3>
        <p class="text-xs text-indigo-500 font-bold mt-2">Acquisizioni recenti</p>
    </div>

    <!-- KPI Card: Margine Mensile -->
    <div class="kpi-card shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label">Margine Lordo</p>
            <h3 class="kpi-value text-emerald-600 dark:text-emerald-400">€ {{ number_format($monthlyMargin, 2, ',', '.') }}</h3>
            <p class="text-xs text-emerald-500 font-bold mt-2">
                {{ number_format(($monthlyMargin / ($monthlyRevenue ?: 1)) * 100, 1) }}% redditività
            </p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full opacity-50"></div>
    </div>
</div>

<div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in">
    <!-- Top Products -->
    <div class="admin-table-container shadow-sm">
        <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Prodotti più Redditizi</h4>
        <div class="space-y-4">
            @foreach($topProducts as $index => $product)
            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-900 transition">
                <div class="flex items-center gap-4">
                    <span class="w-8 h-8 flex items-center justify-center {{ $index == 0 ? 'bg-amber-100 text-amber-600' : 'bg-white dark:bg-slate-800 text-slate-500' }} rounded-xl text-xs font-black shadow-sm">{{ $index + 1 }}</span>
                    <div>
                        <p class="font-bold text-slate-700 dark:text-slate-200 text-sm">{{ $product->NomeProdotto }}</p>
                        <p class="text-[10px] text-slate-400 font-black uppercase">{{ $product->total_sold }} unità vendute</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-black text-emerald-600 dark:text-emerald-400 text-lg">€ {{ number_format($product->profit, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Profitto Totale</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Action Card -->
    <div class="kpi-card kpi-card-dark shadow-xl flex flex-col justify-center p-12">
        <div class="relative z-10 text-center">
            <h4 class="text-3xl font-black mb-4 tracking-tighter">Nuova Vendita</h4>
            <p class="text-slate-400 text-lg mb-10 max-w-sm mx-auto">Inserisci un nuovo ordine nel sistema legacy con decremento automatico di magazzino.</p>
            <a href="{{ route('orders.create') }}" class="btn-premium w-full py-5 text-center text-sm shadow-2xl">Crea Ordine di Vendita</a>
        </div>
        <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-indigo-500/10 rounded-full"></div>
    </div>
</div>
@endsection
