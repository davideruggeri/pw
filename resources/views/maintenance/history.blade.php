@extends('layouts.dashboard')

@section('title', 'Registro Manutenzioni')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/maintenance.css') }}">
@endpush

@section('content')
    <div class="maintenance-container">

        <div class="mb-8 flex justify-between items-end">
            <div>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Registro Interventi</h3>
                <p class="text-slate-500 text-sm">Cronologia completa delle attività tecniche.</p>
            </div>
        </div>

        <div class="maintenance-card">
            <div class="overflow-x-auto">
                <table class="maintenance-table">
                    <thead>
                        <tr class="maintenance-table-header">
                            <th class="maintenance-table-th">Data</th>
                            <th class="maintenance-table-th">Tipo</th>
                            <th class="maintenance-table-th">Descrizione</th>
                            <th class="maintenance-table-th text-right">Durata</th>
                            <th class="maintenance-table-th text-right">Costo</th>
                            <th class="maintenance-table-th">Tecnico</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($logs as $log)
                            <tr class="maintenance-table-tr">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white">
                                    {{ date('d/m/Y', strtotime($log->DataIntervento)) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="maintenance-badge {{ $log->TipoIntervento == 'Straordinaria' ? 'bg-red-100 text-red-600' : 'bg-indigo-100 text-indigo-600' }}">
                                        {{ $log->TipoIntervento }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 dark:text-slate-300 font-medium">{{ $log->NoteIntervento }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($log->OreFermoMacchina, 1, ',', '.') }}
                                        h</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-slate-700 dark:text-slate-300">
                                    € {{ number_format($log->CostoRicambi, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        {{ $log->tecnico->Cognome ?? 'N/D' }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-tighter">Mat:
                                        {{ $log->Matricola_FK }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-800/50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection