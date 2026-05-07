@extends('layouts.dashboard')

@section('title', 'Registro Qualità')

@section('content')
<div class="max-w-6xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-8 flex justify-between items-end">
        <div>
            <a href="{{ route('quality.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Torna alla Dashboard
            </a>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Registro Controlli</h3>
            <p class="text-slate-500 text-sm">Archivio storico dei test di conformità.</p>
        </div>
        <a href="{{ route('quality.create') }}" class="btn-premium py-3 px-6 text-[10px]">
            Nuovo Controllo
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-black/20">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Lotto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Prodotto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Esito</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Scarto (kg)</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Note Difetto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
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
