@extends('layouts.dashboard')

@section('title', 'Dettaglio Reparto: ' . $reparto->NomeReparto)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/departments.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="animate-fade-in">
    <!-- Header Reparto -->
    <div class="flex items-center gap-4 mb-12">
        <a href="{{ route('departments.index') }}" class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 hover:text-indigo-600 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">{{ $reparto->NomeReparto }}</h3>
            <p class="text-sm text-slate-500">Gestione organico e responsabilità del settore.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonna Sinistra: Info e Responsabile -->
        <div class="space-y-6">
            <!-- Card Responsabile -->
            <div class="kpi-card shadow-sm">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Responsabile Attuale</h4>
                
                @if($reparto->responsabile)
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg">
                            {{ substr($reparto->responsabile->Nome, 0, 1) }}{{ substr($reparto->responsabile->Cognome, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $reparto->responsabile->Nome }} {{ $reparto->responsabile->Cognome }}</p>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-tight">{{ $reparto->responsabile->ruolo->NomeRuolo ?? 'Manager' }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-xl mb-8">
                        <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">Nessun responsabile assegnato.</p>
                    </div>
                @endif

                <!-- Form per cambiare responsabile -->
                <form action="{{ route('departments.set-responsabile', $reparto->IDReparto) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler cambiare il responsabile di questo reparto? Questo aggiornerà i permessi e la visualizzazione del personale.')">
                    @csrf
                    <label class="text-[9px] font-black text-slate-400 uppercase mb-2 block">Cambia Responsabile</label>
                    <select name="matricola" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs rounded-xl block p-3 mb-4 font-bold outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="" disabled selected>Seleziona un dipendente...</option>
                        @foreach($dipendenti as $dip)
                            <option value="{{ $dip->Matricola }}" {{ $reparto->IDResponsabile_FK == $dip->Matricola ? 'selected' : '' }}>
                                {{ $dip->Nome }} {{ $dip->Cognome }} ({{ $dip->ruolo->NomeRuolo }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full py-4 bg-slate-900 dark:bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 dark:hover:bg-indigo-500 transition-all shadow-xl">
                        Aggiorna Responsabile
                    </button>
                </form>
            </div>

            <!-- Card Statistiche Reparto -->
            <div class="kpi-card kpi-card-dark group">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">{{ $stats['titolo'] }}</h4>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] text-slate-500 font-bold uppercase">{{ $stats['kpi1_label'] }}</p>
                        <p class="text-3xl font-black text-indigo-400">{{ $stats['kpi1_value'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-slate-500 font-bold uppercase">{{ $stats['kpi2_label'] }}</p>
                        <p class="text-3xl font-black text-emerald-400">{{ $stats['kpi2_value'] }}</p>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-500/10 rounded-full group-hover:scale-110 transition duration-500"></div>
            </div>
        </div>

        <!-- Colonna Destra: Elenco Dipendenti -->
        <div class="lg:col-span-2">
            <div class="admin-table-container shadow-sm">
                <div class="mb-6 flex justify-between items-center">
                    <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Organico Reparto ({{ $dipendenti->count() }})</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Nominativo</th>
                                <th>Ruolo</th>
                                <th class="text-right">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($dipendenti as $dip)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 flex items-center justify-center font-bold text-[10px]">
                                            {{ substr($dip->Nome, 0, 1) }}{{ substr($dip->Cognome, 0, 1) }}
                                        </div>
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $dip->Nome }} {{ $dip->Cognome }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs text-slate-500">{{ $dip->ruolo->NomeRuolo ?? 'N/D' }}</span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('employees.edit', $dip->Matricola) }}" class="text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest hover:underline">
                                        Modifica
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
