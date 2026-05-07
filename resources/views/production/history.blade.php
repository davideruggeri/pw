@extends('layouts.dashboard')

@section('title', 'Storico Produzione')

@section('content')
<div class="max-w-6xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-8 flex justify-between items-end">
        <div>
            <a href="{{ route('production.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Torna alla Dashboard
            </a>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Archivio Lotti</h3>
            <p class="text-slate-500 text-sm">Registro completo della produzione ceramica.</p>
        </div>
        <a href="{{ route('production.create') }}" class="btn-premium py-3 px-6 text-[10px]">
            Nuovo Inserimento
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-black/20">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Lotto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data / Ora</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Prodotto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Quantità</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Costo Energia</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsabile</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
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
