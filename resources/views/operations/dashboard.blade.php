@extends('layouts.dashboard')

@section('title', 'Produzione & Operations - Overview')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="admin-kpi-grid animate-fade-in">
    <!-- KPI Card: Volume Produzione -->
    <div class="kpi-card shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label">Volume Mensile</p>
            <h3 class="kpi-value">{{ number_format($totalQty, 0, ',', '.') }} <span class="text-xs uppercase ml-1">kg</span></h3>
            <p class="text-xs text-emerald-500 font-bold mt-2 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                </svg>
                Output Altoforno
            </p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/20 rounded-full opacity-50"></div>
    </div>

    <!-- KPI Card: Costi Energetici -->
    <div class="kpi-card shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label">Costi Energia (Stima)</p>
            <h3 class="kpi-value">€ {{ number_format($totalEnergy, 2, ',', '.') }}</h3>
            <p class="text-xs text-amber-500 font-bold mt-2">Tariffe Industriali</p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-50 dark:bg-amber-900/20 rounded-full opacity-50"></div>
    </div>

    <!-- KPI Card: Tasso di Scarto -->
    <div class="kpi-card shadow-sm group">
        <div class="relative z-10">
            <p class="kpi-label">Tasso di Scarto</p>
            <h3 class="kpi-value {{ $rejectionRate > 3 ? 'text-red-600 dark:text-red-400' : '' }}">{{ number_format($rejectionRate, 2) }}%</h3>
            <p class="text-xs {{ $rejectionRate > 3 ? 'text-red-400' : 'text-indigo-500' }} font-bold mt-2">
                {{ $rejectionRate > 3 ? 'Sopra soglia' : 'Qualità Ottimale' }}
            </p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-red-50 dark:bg-red-900/20 rounded-full opacity-50"></div>
    </div>
</div>

<div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
    <!-- Elenco Produzione Recente -->
    <div class="lg:col-span-2 admin-table-container shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Log Produzione & Qualità</h4>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ultimi 10 Lotti</span>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="px-2">Data/Ora</th>
                        <th class="px-2">Prodotto</th>
                        <th class="px-2 text-center">Output OK</th>
                        <th class="px-2">Status Qualità</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @foreach($recentLogs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 transition">
                        <td class="px-2 text-slate-500">{{ date('d/m H:i', strtotime($log->DataProduzione)) }}</td>
                        <td class="px-2 font-bold text-slate-800 dark:text-white">{{ $log->prodotto->Descrizione }}</td>
                        <td class="px-2 text-center font-black text-indigo-600 dark:text-indigo-400">{{ number_format($log->QuantitaProdotta, 0, ',', '.') }}</td>
                        <td class="px-2">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase">Certificato</span>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        @if($qualityIssues->count() > 0)
        <div class="kpi-card bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-800 shadow-sm">
            <h4 class="text-[10px] font-black text-red-700 dark:text-red-400 uppercase tracking-widest mb-6">Anomalie Qualità</h4>
            <div class="space-y-4">
                @foreach($qualityIssues as $issue)
                <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-red-100 dark:border-red-900/50 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-slate-800 dark:text-white">{{ $issue->produzione->prodotto->Descrizione }}</p>
                        <span class="text-[10px] font-black text-red-600">-{{ $issue->QuantitaScartata }}kg</span>
                    </div>
                    <p class="text-[10px] text-slate-500 italic">"{{ $issue->NoteDifetto }}"</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Sezione Manutenzione -->
        <div class="kpi-card bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 shadow-sm">
            <h4 class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-6">Focus Manutenzione</h4>
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Downtime</p>
                    <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalDowntime }}h</p>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Costo Ricambi</p>
                    <p class="text-2xl font-black text-slate-800 dark:text-white">€{{ number_format($totalMaintenanceCost, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="space-y-4">
                @foreach($recentMaintenance as $m)
                <div class="flex items-center gap-3 text-xs">
                    <div class="w-2 h-2 rounded-full {{ $m->TipoIntervento == 'Straordinaria' ? 'bg-red-500' : 'bg-indigo-500' }}"></div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-700 dark:text-slate-200">{{ $m->TipoIntervento }} ({{ $m->OreFermoMacchina }}h)</p>
                        <p class="text-[10px] text-slate-400 uppercase font-black">{{ date('d/m', strtotime($m->DataIntervento)) }} - {{ $m->tecnico->Cognome }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="kpi-card shadow-sm p-8">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Azioni Rapide</h4>
            <button class="w-full py-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition shadow-lg mb-4">
                Registra Lotto Produzione
            </button>
            <button class="w-full py-4 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-800 transition">
                Segnala Fermo Macchina
            </button>
        </div>
    </div>
</div>
@endsection
