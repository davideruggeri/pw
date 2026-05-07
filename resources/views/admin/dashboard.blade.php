@extends('layouts.dashboard')

@section('title', 'Amministrazione - Overview')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="admin-kpi-grid animate-fade-in">
    <!-- KPI Card: Fatturato -->
    <div class="kpi-card shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label">Fatturato Lordo</p>
            <h3 class="kpi-value">€ {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-2">Volume totale vendite</p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/20 rounded-full opacity-50"></div>
    </div>

    <!-- KPI Card: Costi Operativi -->
    <div class="kpi-card shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label">Costi Industriali</p>
            <h3 class="kpi-value text-red-600 dark:text-red-400">€ {{ number_format($cogs + $energyCosts + $maintenanceCosts + $qualityLosses, 0, ',', '.') }}</h3>
            <p class="text-xs text-red-400 font-bold mt-2">Energia + Manut. + Scarti</p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-red-50 dark:bg-red-900/20 rounded-full opacity-50"></div>
    </div>

    <!-- KPI Card: EBITDA -->
    <div class="kpi-card kpi-card-dark shadow-lg group">
        <div class="relative z-10">
            <p class="kpi-label text-slate-400">EBITDA (Margine Operativo)</p>
            <h3 class="kpi-value text-emerald-400">€ {{ number_format($ebitda, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-slate-500 mt-2 uppercase font-black">Utile al lordo di tasse/ammortamenti</p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-emerald-500/10 rounded-full"></div>
    </div>

    <!-- KPI Card: Personale -->
    <div class="kpi-card shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label">Costo Lavoro</p>
            <h3 class="kpi-value">€ {{ number_format($laborCosts, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-500 mt-2">{{ $totalEmployees }} dipendenti attivi</p>
        </div>
    </div>
</div>

<!-- Bilancio Dettagliato -->
<div class="financial-container shadow-sm animate-fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h4 class="text-xl font-bold text-slate-800 dark:text-white">Conto Economico Mensile</h4>
            <p class="text-sm text-slate-500">Analisi consolidata dei flussi di cassa per reparto.</p>
        </div>
        <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 uppercase">Periodo: {{ now()->translatedFormat('F Y') }}</span>
    </div>

    <div class="space-y-6">
        <!-- Revenue -->
        <div class="financial-row">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                </div>
                <div>
                    <p class="font-bold text-slate-800 dark:text-slate-200">Ricavi da Vendite</p>
                    <p class="text-[10px] text-slate-400 uppercase font-black">Reparto Commerciale</p>
                </div>
            </div>
            <p class="text-lg font-black text-slate-900 dark:text-emerald-400">+ € {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>

        <!-- Costs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 border border-slate-100 dark:border-slate-800 rounded-2xl flex justify-between items-center">
                <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Costo Produzione (Energia)</span>
                <span class="text-sm font-black text-red-600 dark:text-red-400">- € {{ number_format($energyCosts, 0, ',', '.') }}</span>
            </div>
            <div class="p-4 border border-slate-100 dark:border-slate-800 rounded-2xl flex justify-between items-center">
                <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Manutenzione & Ricambi</span>
                <span class="text-sm font-black text-red-600 dark:text-red-400">- € {{ number_format($maintenanceCosts, 0, ',', '.') }}</span>
            </div>
            <div class="p-4 border border-slate-100 dark:border-slate-800 rounded-2xl flex justify-between items-center">
                <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Perdite per Scarti Qualità</span>
                <span class="text-sm font-black text-red-600 dark:text-red-400">- € {{ number_format($qualityLosses, 0, ',', '.') }}</span>
            </div>
            <div class="p-4 border border-slate-100 dark:border-slate-800 rounded-2xl flex justify-between items-center">
                <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Salari e Oneri Sociali</span>
                <span class="text-sm font-black text-red-600 dark:text-red-400">- € {{ number_format($laborCosts, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Final Margin -->
        <div class="final-net-box">
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Utile Operativo Netto</p>
                <h3 class="text-3xl font-black {{ $ebitda > 0 ? 'text-emerald-400' : 'text-red-400' }}">€ {{ number_format($ebitda, 0, ',', '.') }}</h3>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Margine su Ricavi</p>
                <p class="text-xl font-bold">{{ number_format(($ebitda / ($totalRevenue ?: 1)) * 100, 1) }}%</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activity -->
    <div class="lg:col-span-2 admin-table-container shadow-sm">
        <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Ultimi Ordini</h4>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="px-2">ID Ordine</th>
                        <th class="px-2">Cliente</th>
                        <th class="px-2">Data</th>
                        <th class="px-2">Stato</th>
                        <th class="px-2 text-right">Totale</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @foreach($recentOrders as $ordine)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-2 font-medium">
                            <a href="{{ route('orders.show', $ordine->IDOrdineVendita) }}" class="text-indigo-600 hover:underline font-black">
                                #{{ $ordine->IDOrdineVendita }}
                            </a>
                        </td>
                        <td class="px-2 text-slate-700 dark:text-slate-300 font-semibold">{{ $ordine->cliente->Nome ?? 'N/D' }}</td>
                        <td class="px-2 text-slate-500">{{ $ordine->Data ? date('d/m/Y', strtotime($ordine->Data)) : '-' }}</td>
                        <td class="px-2">
                            @php
                                $statusClasses = [
                                    'Inviato' => 'bg-amber-100 text-amber-700',
                                    'Completato' => 'bg-emerald-100 text-emerald-700',
                                    'Annullato' => 'bg-red-100 text-red-700'
                                ];
                            @endphp
                            <span class="px-2 py-1 {{ $statusClasses[$ordine->Stato] ?? 'bg-slate-100 text-slate-700' }} rounded-lg text-[10px] font-bold uppercase">
                                {{ $ordine->Stato }}
                            </span>
                        </td>
                        <td class="px-2 text-right font-bold text-slate-800 dark:text-white">
                            € {{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="kpi-card kpi-card-dark shadow-lg">
        <h4 class="text-lg font-bold mb-6">Azioni Rapide</h4>
        <div class="space-y-4">
            <a href="{{ route('employees.create') }}" class="w-full btn-premium justify-between py-4">
                Nuovo Dipendente
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="{{ route('reports.index') }}" class="w-full bg-slate-800 hover:bg-slate-700 text-white py-4 rounded-2xl flex items-center justify-between px-6 transition font-semibold border border-slate-700">
                Genera Report KPI
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
