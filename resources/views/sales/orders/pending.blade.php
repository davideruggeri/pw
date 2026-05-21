@extends('layouts.dashboard')

@section('title', 'Commerciale - Approvazione Ordini')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sales-orders.css') }}">

<div class="premium-page-container animate-fade-in">

    <div class="mb-12 flex justify-between items-end">
        <div>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Attesa Approvazione</h3>
            <p class="text-slate-500 text-sm">Revisione e validazione degli ordini inoltrati dai clienti.</p>
        </div>
        <div class="bg-amber-100 text-amber-700 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-200">
            {{ $orders->count() }} ordini da gestire
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="approval-card p-20 text-center flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <h4 class="text-xl font-black text-slate-400 uppercase tracking-widest">Coda Vuota</h4>
            <p class="text-slate-500 mt-2">Tutti gli ordini sono stati elaborati correttamente.</p>
            <a href="{{ route('orders.index') }}" class="mt-8 text-xs font-black text-indigo-600 uppercase tracking-widest underline decoration-2 underline-offset-8">Vai all'Archivio</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($orders as $ordine)
                <div class="approval-card group">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ordine ID</span>
                                <span class="text-lg font-black text-slate-900 dark:text-white">#{{ $ordine->IDOrdineVendita }}</span>
                            </div>
                            <span class="status-badge status-inviato">In Attesa</span>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="client-avatar-placeholder">
                                {{ substr($ordine->cliente->Nome ?? 'C', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 dark:text-white leading-tight">{{ $ordine->cliente->Nome ?? 'Cliente Sconosciuto' }}</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $ordine->Data }}</p>
                            </div>
                        </div>

                        <div class="order-detail-mini">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Riepilogo Prodotti</p>
                            <div class="space-y-3">
                                @foreach($ordine->dettagliVendita->take(3) as $dettaglio)
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-slate-600 dark:text-slate-400 font-medium line-clamp-1 flex-1">{{ $dettaglio->prodotto->Descrizione }}</span>
                                        <span class="font-black text-slate-900 dark:text-slate-200 ml-4">x{{ $dettaglio->QuantitaRichiesta }}</span>
                                    </div>
                                @endforeach
                                @if(count($ordine->dettagliVendita) > 3)
                                    <p class="text-[10px] text-indigo-500 font-black uppercase tracking-widest mt-2">+{{ count($ordine->dettagliVendita) - 3 }} altri articoli</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800/50">
                        <div class="flex justify-between items-end mb-6">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Totale da Pagare</p>
                                <p class="text-2xl font-black text-slate-900 dark:text-white">€ {{ number_format($ordine->totale_ordine, 2, ',', '.') }}</p>
                            </div>
                            
                            <div class="flex gap-2">
                                <form action="{{ route('orders.reject', $ordine->IDOrdineVendita) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-reject" title="Rifiuta Ordine">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </form>

                                <form action="{{ route('orders.approve', $ordine->IDOrdineVendita) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-approve w-14 h-14 rounded-2xl flex items-center justify-center text-white transition-all hover:scale-110 active:scale-95 shadow-xl" title="Approva Ordine">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <a href="{{ route('customer.orders.show', $ordine->IDOrdineVendita) }}" class="block text-center text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">Visualizza Dettagli Completi</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
