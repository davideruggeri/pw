@extends('layouts.dashboard')

@section('title', "Commerciale - Dettaglio Ordine #$ordine->IDOrdineVendita")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales-orders.css') }}">
@endpush

@section('content')
<div class="logistics-container animate-fade-in max-w-6xl mx-auto">
    
    <!-- Top Action Bar -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <a href="{{ url()->previous() == route('orders.index') ? route('orders.index') : route('sales.dashboard') }}" 
           class="group flex items-center gap-3 px-5 py-2.5 bg-white dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800/50 text-[10px] font-black text-slate-400 hover:text-indigo-600 transition-all uppercase tracking-widest hover:border-indigo-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Torna all'Archivio
        </a>
        <div class="flex gap-3">
            <button class="bg-white dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 px-6 py-3 rounded-2xl border border-slate-100 dark:border-slate-800/50 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                Esporta PDF
            </button>
            <button class="bg-indigo-600 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all hover:-translate-y-1">
                Conferma Spedizione
            </button>
        </div>
    </div>

    <div class="logistics-card p-10 md:p-16 border-0 shadow-2xl relative overflow-hidden">
        <!-- Watermark Background -->
        <div class="absolute top-10 right-10 opacity-[0.03] dark:opacity-[0.05] pointer-events-none select-none">
            <h1 class="text-[12rem] font-black tracking-tighter leading-none">ORDER</h1>
        </div>
        
        <!-- Main Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8 mb-16 relative z-10">
            <div>
                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <span class="px-5 py-2 bg-indigo-600 text-white rounded-2xl text-xs font-black tracking-widest uppercase">Ordine Cliente</span>
                    <span class="status-badge status-{{ strtolower($ordine->Stato) }} scale-125 origin-left ml-4">
                        {{ $ordine->Stato }}
                    </span>
                </div>
                <h2 class="text-6xl md:text-7xl font-black text-slate-900 dark:text-white tracking-tighter leading-none">
                    #{{ $ordine->IDOrdineVendita }}
                </h2>
                <div class="mt-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-xs font-black text-indigo-600">
                        {{ substr($ordine->venditore->Nome ?? 'A', 0, 1) }}
                    </div>
                    <p class="text-sm text-slate-500 font-bold">
                        Gestito da: <span class="text-slate-900 dark:text-white">{{ $ordine->venditore->Nome ?? 'N/D' }} {{ $ordine->venditore->Cognome ?? '' }}</span>
                    </p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-2">Eseguito il</p>
                <p class="text-2xl font-black text-slate-900 dark:text-white uppercase leading-none">
                    {{ \Carbon\Carbon::parse($ordine->Data)->translatedFormat('d F Y') }}
                </p>
                <p class="text-sm font-bold text-slate-400 mt-2">{{ \Carbon\Carbon::parse($ordine->Data)->format('H:i') }} • ID Sessione #392</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-16 relative z-10">
            <div class="group">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-4 group-hover:text-indigo-600 transition-colors">Destinatario</span>
                <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800/50">
                    <p class="text-xl font-black text-slate-900 dark:text-white leading-tight mb-2">{{ $ordine->cliente->Nome ?? 'N/D' }}</p>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        {{ $ordine->cliente->IndirizzoSpedizione ?? 'Indirizzo non specificato' }}<br>
                        P.IVA/CF: <span class="text-slate-700 dark:text-slate-300">{{ $ordine->cliente->PartitaIVA ?? 'N/D' }}</span>
                    </p>
                </div>
            </div>
            
            <div class="group">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-4 group-hover:text-indigo-600 transition-colors">Termini di Vendita</span>
                <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800/50">
                    <p class="text-xl font-black text-slate-900 dark:text-white leading-tight mb-2">Bonifico Bancario</p>
                    <p class="text-xs text-slate-500 font-medium">Scadenza: <span class="text-slate-900 dark:text-white font-bold">Netto 30 Giorni</span></p>
                </div>
            </div>

            <div class="bg-indigo-600 p-8 rounded-[2rem] shadow-2xl shadow-indigo-600/30 text-white relative overflow-hidden group hover:-translate-y-1 transition-all">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <span class="text-[9px] font-black text-indigo-200 uppercase tracking-widest block mb-4">Totale Ordine</span>
                <p class="text-4xl font-black tracking-tighter tabular-nums">
                    € {{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2, ',', '.') }}
                </p>
                <div class="mt-4 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-indigo-300 animate-pulse"></div>
                    <p class="text-[10px] text-indigo-100 uppercase font-black">Pronto per Fatturazione</p>
                </div>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="relative z-10">
            <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest mb-6 px-2">Dettaglio Articoli</h4>
            <div class="border border-slate-100 dark:border-slate-800/50 rounded-[2.5rem] overflow-hidden shadow-sm">
                <table class="logistics-table">
                    <thead>
                        <tr class="logistics-table-header bg-slate-50 dark:bg-slate-900/30">
                            <th class="px-8">Descrizione Prodotto</th>
                            <th class="text-center">Quantità</th>
                            <th class="text-right">Unitario</th>
                            <th class="text-right px-8">Totale riga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/30">
                        @foreach($ordine->dettagliVendita as $dettaglio)
                        <tr class="logistics-table-tr group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                                        📦
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800 dark:text-white transition-colors">{{ $dettaglio->prodotto->Descrizione }}</p>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">SKU: {{ $dettaglio->CodiceUnivoco_FK }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 text-center">
                                <span class="px-4 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-xl text-xs font-black text-slate-700 dark:text-slate-300 tabular-nums">
                                    {{ number_format($dettaglio->QuantitaRichiesta, 0, ',', '.') }} pz
                                </span>
                            </td>
                            <td class="py-6 text-right font-medium text-slate-400 tabular-nums text-xs">
                                € {{ number_format($dettaglio->PrezzoApplicato, 2, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-sm font-black text-slate-900 dark:text-white tabular-nums">
                                    € {{ number_format($dettaglio->QuantitaRichiesta * $dettaglio->PrezzoApplicato, 2, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Decoration -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
    </div>
</div>
@endsection
