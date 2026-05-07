@extends('layouts.dashboard')

@section('title', "Dettaglio Ordine #$ordine->IDOrdineVendita")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/order-detail.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 animate-fade-in">
    
    <div class="order-detail-card">
        <!-- Decorative background glow -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <!-- Header -->
        <div class="order-detail-header">
            <div class="flex items-center gap-6">
                <a href="{{ route('customer.orders') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:border-indigo-500 transition-all shadow-lg active:scale-95 group" title="Torna allo storico">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tighter mb-1">Ordine #{{ $ordine->IDOrdineVendita }}</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-[9px]">Creato il {{ \Carbon\Carbon::parse($ordine->Data)->format('d F Y') }}</p>
                </div>
            </div>
            <div class="flex flex-col items-end gap-4">
                <span class="order-status-badge">
                    {{ $ordine->StatoConsegna ?? $ordine->Stato ?? 'In Elaborazione' }}
                </span>
                <p class="text-[10px] text-slate-600 font-black uppercase tracking-widest">Metodo Pagamento: <span class="text-slate-400">Bonifico</span></p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-block">
                <span class="info-label">Indirizzo di Spedizione</span>
                <p class="info-value">{{ $ordine->cliente->Nome }}</p>
                <p class="text-xs text-slate-500 mt-3 leading-relaxed">{{ $ordine->cliente->Indirizzo ?? 'Indirizzo non disponibile' }}</p>
            </div>

            <div class="info-block">
                <span class="info-label">Supporto Clienti</span>
                <p class="info-value">Centro Assistenza</p>
                <p class="text-xs text-slate-500 mt-3 leading-relaxed">Hai bisogno di aiuto? Contattaci citando il numero ordine #{{ $ordine->IDOrdineVendita }}.</p>
            </div>

            <div class="info-block">
                <span class="info-label">Riepilogo Finanziario</span>
                <p class="info-value text-indigo-400 text-2xl">€ {{ number_format($ordine->totale_ordine, 2, ',', '.') }}</p>
                <p class="text-[10px] text-slate-600 uppercase font-black mt-2">Iva inclusa (22%)</p>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="detail-table-wrapper">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Prodotto</th>
                        <th class="text-center">Quantità</th>
                        <th class="text-right">Prezzo Unitario</th>
                        <th class="text-right">Totale</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordine->dettagliVendita as $dettaglio)
                    <tr>
                        <td>
                            <div class="product-cell">
                                <div class="product-icon">📦</div>
                                <div class="product-info">
                                    <h4>{{ $dettaglio->prodotto->Descrizione }}</h4>
                                    <span>CODE: {{ $dettaglio->CodiceUnivoco_FK }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="qty-badge">{{ $dettaglio->QuantitaRichiesta }}</span>
                        </td>
                        <td class="text-right">
                            <span class="price-text text-slate-400">€ {{ number_format($dettaglio->PrezzoApplicato, 2, ',', '.') }}</span>
                        </td>
                        <td class="text-right">
                            <span class="price-text">€ {{ number_format($dettaglio->QuantitaRichiesta * $dettaglio->PrezzoApplicato, 2, ',', '.') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Final Summary -->
        <div class="total-summary">
            <div class="total-row">
                <span class="total-label">Totale Ordine</span>
                <span class="total-amount">€ {{ number_format($ordine->totale_ordine, 2, ',', '.') }}</span>
            </div>
        </div>

    </div>
</div>
@endsection
