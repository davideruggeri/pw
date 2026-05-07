@extends('layouts.dashboard')

@section('title', 'Registro Manutenzioni')

@section('content')
<div class="max-w-6xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-8 flex justify-between items-end">
        <div>
            <a href="{{ route('maintenance.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Torna alla Dashboard
            </a>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Registro Interventi</h3>
            <p class="text-slate-500 text-sm">Cronologia completa delle attività tecniche.</p>
        </div>
        <a href="{{ route('maintenance.create') }}" class="btn-premium py-3 px-6 text-[10px]">
            Nuovo Intervento
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-black/20">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tipo</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Descrizione</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Durata</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Costo</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tecnico</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                        <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white">
                            {{ date('d/m/Y', strtotime($log->DataIntervento)) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest {{ $log->TipoIntervento == 'Straordinaria' ? 'bg-red-100 text-red-600' : 'bg-indigo-100 text-indigo-600' }}">
                                {{ $log->TipoIntervento }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600 dark:text-slate-300 font-medium">{{ $log->NoteIntervento }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($log->OreFermoMacchina, 1, ',', '.') }} h</span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-slate-700 dark:text-slate-300">
                            € {{ number_format($log->CostoRicambi, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $log->tecnico->Cognome ?? 'N/D' }}</p>
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
