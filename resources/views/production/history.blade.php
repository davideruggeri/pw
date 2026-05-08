@extends('layouts.dashboard')

@section('title', 'Storico Produzione')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/production.css') }}">
@endpush

@section('content')
<div class="production-container">
    
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Archivio Lotti</h3>
            <p class="text-slate-500 text-sm">Registro completo della produzione ceramica.</p>
        </div>
        <a href="{{ route('production.create') }}" class="btn-premium py-3 px-6 text-[10px]">
            Nuovo Inserimento
        </a>
    </div>

    <div class="production-card">
        <div class="overflow-x-auto">
            <table class="production-table">
                <thead>
                    <tr class="production-table-header">
                        <th class="production-table-th">ID Lotto</th>
                        <th class="production-table-th">Data / Ora</th>
                        <th class="production-table-th">Prodotto</th>
                        <th class="production-table-th text-right">Quantità</th>
                        <th class="production-table-th text-right">Costo Energia</th>
                        <th class="production-table-th">Responsabile</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($logs as $log)
                    <tr class="production-table-tr">
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">#{{ $log->IDLogProduzione }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ date('d/m/Y', strtotime($log->DataProduzione)) }}</p>
                            <p class="text-[10px] text-slate-500">{{ date('H:i', strtotime($log->DataProduzione)) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $log->prodotto->Descrizione }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-slate-900 dark:text-white">
                                {{ number_format($log->QuantitaProdotta, 0, ',', '.') }} kg
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-slate-600 dark:text-slate-400">
                            € {{ number_format($log->CostoEnergiaStimato, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $log->responsabile->Cognome ?? 'N/D' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-tighter">Mat: {{ $log->Matricola_FK }}</p>
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
