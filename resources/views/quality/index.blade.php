@extends('layouts.dashboard')

@section('title', 'Qualità - Overview')

@section('content')
<div class="max-w-6xl mx-auto py-10 animate-fade-in">

    <!-- KPI Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tasso di Scarto (Mese)</p>
                <h3 class="text-4xl font-black {{ $rejectionRate > 3 ? 'text-rose-500' : 'text-emerald-500' }} tracking-tighter">
                    {{ number_format($rejectionRate, 2, ',', '.') }}%
                </h3>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kg Scartati</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">
                    {{ number_format($totalRejected, 0, ',', '.') }} kg
                </h3>
            </div>
        </div>
        
        <!-- Azioni Rapide -->
        <div class="bg-slate-900 p-8 rounded-3xl shadow-xl border border-slate-800 flex items-center justify-between">
            <div>
                <h4 class="text-white font-black text-lg tracking-tight">Lotti da verificare?</h4>
                <p class="text-slate-400 text-xs">Esegui subito il controllo qualità</p>
            </div>
            <a href="{{ route('quality.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95">
                Registra Controllo
            </a>
        </div>
    </div>

    <!-- Anteprima Controlli -->
    <div class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Controlli Recenti</h4>
            <a href="{{ route('quality.history') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">Vedi tutto il registro</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($latestLogs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ date('d/m/Y', strtotime($log->DataControllo)) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-black text-slate-800 dark:text-white">Lotto #{{ $log->IDLogProduzione_FK }}</p>
                            <p class="text-[10px] text-slate-500 uppercase font-black">{{ $log->produzione->prodotto->Descrizione ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest {{ $log->Esito == 'PASS' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                {{ $log->Esito }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black {{ $log->QuantitaScartata > 0 ? 'text-rose-500' : 'text-slate-400' }}">
                                {{ number_format($log->QuantitaScartata, 0, ',', '.') }} kg
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm">Nessun controllo registrato recentemente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
