@extends('layouts.dashboard')

@section('title', 'Produzione - Overview')

@section('content')
<div class="premium-page-container">

    <!-- KPI Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="premium-card p-8">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Volume Prodotto (Mese)</p>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                {{ number_format($totalProducedMonth, 0, ',', '.') }} <span class="text-sm font-bold text-slate-500 uppercase">kg</span>
            </h3>
        </div>
        
        <!-- Azioni Rapide -->
        <div class="bg-indigo-600 p-8 rounded-3xl shadow-xl shadow-indigo-500/20 flex items-center justify-between">
            <div>
                <h4 class="text-white font-black text-lg tracking-tight">Pronto per un nuovo lotto?</h4>
                <p class="text-indigo-100 text-xs">Registra subito la produzione odierna</p>
            </div>
            <a href="{{ route('production.create') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition-all active:scale-95">
                Registra Produzione
            </a>
        </div>
    </div>

    <!-- Anteprima Ultimi Lotti -->
    <div class="premium-card">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Attività Recente</h4>
            <a href="{{ route('production.history') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">Vedi tutto lo storico</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="premium-table">
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($latestLogs as $log)
                    <tr class="premium-table-tr">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ date('d/m/Y', strtotime($log->DataProduzione)) }}</p>
                            <p class="text-[10px] text-slate-500">{{ date('H:i', strtotime($log->DataProduzione)) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $log->prodotto->Descrizione }}</p>
                            <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest">Lotto #{{ $log->IDLogProduzione }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-block px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg text-sm font-black">
                                {{ number_format($log->QuantitaProdotta, 0, ',', '.') }} kg
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-slate-500 text-sm">Nessuna produzione registrata recentemente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
