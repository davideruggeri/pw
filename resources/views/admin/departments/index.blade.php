@extends('layouts.dashboard')

@section('title', 'Gestione Reparti')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/departments.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="animate-fade-in">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">Struttura Aziendale</h3>
            <p class="text-sm text-slate-500">Monitora le performance e l'organico di ogni settore aziendale.</p>
        </div>
    </div>

    <div class="dept-grid">
        @foreach($reparti as $reparto)
        <a href="{{ route('departments.show', $reparto->IDReparto) }}" class="dept-card group block no-underline">
            <div class="flex justify-between items-start mb-6">
                <div class="dept-icon-box">
                    {{ substr($reparto->NomeReparto, 0, 1) }}
                </div>
                <span class="px-3 py-1 bg-slate-50 dark:bg-slate-900 text-slate-400 text-[9px] font-black uppercase rounded-full border border-slate-100 dark:border-slate-800">
                    ID: {{ $reparto->IDReparto }}
                </span>
            </div>
            
            <h4 class="dept-title">{{ $reparto->NomeReparto }}</h4>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-8">
                Responsabile: 
                <span class="text-indigo-600 dark:text-indigo-400">
                    {{ $reparto->responsabile ? $reparto->responsabile->Nome . ' ' . $reparto->responsabile->Cognome : 'Da Assegnare' }}
                </span>
            </p>

            <div class="dept-stats-row">
                <div>
                    <p class="dept-stat-label">Organico</p>
                    <p class="dept-stat-value">{{ $reparto->dipendenti_count }} <span class="text-[10px] text-slate-400 font-bold">PERS.</span></p>
                </div>
                <div>
                    <p class="dept-stat-label">{{ $reparto->kpi_label }}</p>
                    <p class="dept-stat-value {{ $reparto->kpi_color }}">{{ $reparto->kpi_value }}</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <span class="text-[10px] font-black uppercase text-indigo-600 dark:text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                    Gestisci Reparto <span>→</span>
                </span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
