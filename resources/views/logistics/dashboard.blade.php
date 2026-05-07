@extends('layouts.dashboard')

@section('title', 'Logistica - Overview')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="admin-kpi-grid animate-fade-in">
    <!-- KPI Card: Valore Magazzino -->
    <div class="kpi-card kpi-card-dark shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label text-slate-400">Valore Immobilizzato</p>
            <h3 class="kpi-value text-white">€ {{ number_format($totalWarehouseValue, 2, ',', '.') }}</h3>
            <p class="text-xs text-slate-500 mt-2">Valutazione al costo di produzione</p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/10 rounded-full opacity-50"></div>
    </div>

    <!-- KPI Card: Salute Stock -->
    <div class="kpi-card shadow-sm">
        <p class="kpi-label">Salute Inventario</p>
        <div class="mt-6 flex items-center gap-2">
            <div class="flex-1 h-3 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden flex">
                <div class="bg-red-500 h-full" style="width: {{ ($inventoryStatus['critical'] / $totalProducts) * 100 }}%"></div>
                <div class="bg-amber-400 h-full" style="width: {{ ($inventoryStatus['warning'] / $totalProducts) * 100 }}%"></div>
                <div class="bg-emerald-500 h-full" style="width: {{ ($inventoryStatus['ok'] / $totalProducts) * 100 }}%"></div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-3 text-[9px] font-black uppercase tracking-tighter">
            <div class="text-red-500">Zero: {{ $inventoryStatus['critical'] }}</div>
            <div class="text-amber-500 text-center">Low: {{ $inventoryStatus['warning'] }}</div>
            <div class="text-emerald-500 text-right">OK: {{ $inventoryStatus['ok'] }}</div>
        </div>
    </div>

    <!-- KPI Card: Allerta Sottoscorta -->
    <div class="kpi-card shadow-sm {{ $sottoScortaCount > 0 ? 'border-l-4 border-red-500' : 'border-l-4 border-emerald-500' }}">
        <p class="kpi-label">Criticità Riordino</p>
        <h3 class="kpi-value {{ $sottoScortaCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $sottoScortaCount }}</h3>
        <p class="text-xs {{ $sottoScortaCount > 0 ? 'text-red-400' : 'text-emerald-400' }} font-bold mt-2">
            {{ $sottoScortaCount > 0 ? 'Prodotti sotto soglia minima' : 'Giacenze ottimali' }}
        </p>
    </div>
</div>

@if($sottoScortaCount > 0)
<div class="mt-12 admin-table-container shadow-sm animate-fade-in">
    <div class="flex items-center justify-between mb-8">
        <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Analisi Prodotti Sottoscorta
        </h4>
        <button class="px-5 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-500 transition shadow-lg">Genera Ordine Acquisto</button>
    </div>
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="px-2">Codice</th>
                    <th class="px-2">Prodotto</th>
                    <th class="px-2 text-right">Giacenza</th>
                    <th class="px-2 text-right">Target Min.</th>
                    <th class="px-2 text-center">Urgente</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                @foreach($sottoScorta as $prodotto)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 transition">
                    <td class="px-2 font-mono text-xs text-slate-500">#{{ $prodotto->CodiceUnivoco }}</td>
                    <td class="px-2 font-bold text-slate-800 dark:text-slate-200">{{ $prodotto->Descrizione ?? $prodotto->NomeProdotto }}</td>
                    <td class="px-2 text-right font-black {{ $prodotto->QuantitaGiacenza == 0 ? 'text-red-700 dark:text-red-500' : 'text-red-500 dark:text-red-400' }}">
                        {{ $prodotto->QuantitaGiacenza }}
                    </td>
                    <td class="px-2 text-right text-slate-500 font-bold">{{ $prodotto->ScortaMinima }}</td>
                    <td class="px-2 text-center">
                        <span class="w-2.5 h-2.5 rounded-full inline-block {{ $prodotto->QuantitaGiacenza == 0 ? 'bg-red-600 animate-pulse shadow-[0_0_8px_rgba(220,38,38,0.6)]' : 'bg-amber-400' }}"></span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
