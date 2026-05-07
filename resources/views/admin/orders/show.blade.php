@extends('layouts.dashboard')

@section('title', "Dettaglio Ordine #$ordine->IDOrdineVendita")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-order-detail.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="max-w-6xl mx-auto py-8 animate-fade-in">
    
    <!-- Back Link -->
    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-indigo-600 flex items-center gap-2 transition font-bold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Torna alla Dashboard
        </a>
    </div>

    <div class="glass-card p-10 bg-white dark:bg-slate-800 border-0 shadow-xl">
        
        <!-- Header -->
        <div class="order-detail-header">
            <div>
                <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Ordine #{{ $ordine->IDOrdineVendita }}</h2>
                <p class="text-slate-500 mt-1">Gestito da: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $ordine->venditore->Nome ?? 'N/D' }} {{ $ordine->venditore->Cognome ?? '' }}</span></p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 bg-emerald-500 text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20">
                    {{ $ordine->Stato }}
                </span>
                <p class="text-slate-400 text-xs mt-4 font-mono uppercase tracking-widest">{{ \Carbon\Carbon::parse($ordine->Data)->format('d F Y - H:i') }}</p>
            </div>
        </div>

        <!-- Info Blocks -->
        <div class="info-grid">
            <div class="info-block">
                <span class="info-label">Dettagli Cliente</span>
                <p class="info-value">{{ $ordine->cliente->Nome ?? 'N/D' }}</p>
                <p class="text-xs text-slate-500 mt-2">{{ $ordine->cliente->IndirizzoSpedizione ?? '-' }}</p>
            </div>
            
            <div class="info-block">
                <span class="info-label">Metodo di Pagamento</span>
                <p class="info-value">Bonifico Bancario</p>
                <p class="text-xs text-slate-500 mt-2">Termini: 30gg fine mese</p>
            </div>

            <div class="info-block">
                <span class="info-label">Riepilogo Finanziario</span>
                <p class="info-value text-indigo-600 dark:text-indigo-400 text-2xl">€ {{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 uppercase font-black mt-1">IVA inclusa (22%)</p>
            </div>
        </div>

        <!-- Table -->
        <div class="detail-table-container">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Prodotto</th>
                        <th class="text-center">Quantità</th>
                        <th class="text-right">Prezzo Un.</th>
                        <th class="text-right">Totale</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                    @foreach($ordine->dettagliVendita as $dettaglio)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                        <td class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-900 rounded-lg flex items-center justify-center text-lg">📦</div>
                            <div>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $dettaglio->prodotto->Descrizione }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">CODE: {{ $dettaglio->CodiceUnivoco_FK }}</p>
                            </div>
                        </td>
                        <td class="text-center font-bold text-slate-600 dark:text-slate-400">{{ $dettaglio->QuantitaRichiesta }}</td>
                        <td class="text-right text-slate-500 font-medium">€ {{ number_format($dettaglio->PrezzoApplicato, 2, ',', '.') }}</td>
                        <td class="text-right font-black text-slate-900 dark:text-white">€ {{ number_format($dettaglio->QuantitaRichiesta * $dettaglio->PrezzoApplicato, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer Actions -->
        <div class="mt-12 flex justify-end gap-4">
            <button class="px-8 py-3 bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-bold rounded-xl hover:bg-slate-200 transition">Stampa PDF</button>
            <button class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 transition">Aggiorna Stato</button>
        </div>

    </div>
</div>
@endsection
