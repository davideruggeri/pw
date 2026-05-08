@extends('layouts.dashboard')

@section('title', 'Registro Qualità')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/quality.css') }}">
@endpush

@section('content')
<div class="quality-container">
    
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Registro Controlli</h3>
            <p class="text-slate-500 text-sm">Archivio storico dei test di conformità.</p>
        </div>
        <a href="{{ route('quality.create') }}" class="btn-premium py-3 px-6 text-[10px]">
            Nuovo Controllo
        </a>
    </div>

    <div class="quality-card">
        <div class="overflow-x-auto">
            <table class="quality-table">
                <thead>
                    <tr class="quality-table-header">
                        <th class="quality-table-th">Data</th>
                        <th class="quality-table-th">Lotto</th>
                        <th class="quality-table-th">Prodotto</th>
                        <th class="quality-table-th">Esito</th>
                        <th class="quality-table-th text-right">Scarto (kg)</th>
                        <th class="quality-table-th">Note Difetto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($logs as $log)
                    <tr class="quality-table-tr">
                        <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white">
                            {{ date('d/m/Y', strtotime($log->DataControllo)) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">#{{ $log->IDLogProduzione_FK }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $log->produzione->prodotto->Descrizione ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest {{ $log->Esito == 'PASS' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                {{ $log->Esito }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-black {{ $log->QuantitaScartata > 0 ? 'text-rose-500' : 'text-slate-400' }}">
                            {{ number_format($log->QuantitaScartata, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-500 italic">{{ $log->NoteDifetto ?: '-' }}</p>
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
