@extends('layouts.dashboard')

@section('title', 'I Miei Ordini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders-list.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-4 sm:px-6 lg:px-8 animate-fade-in">

        <!-- Intestazione -->
        <div class="mb-20 pb-12">
            <h2 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-6">Storico Ordini</h2>
            <p class="text-slate-400 text-lg leading-relaxed">Consulta l'elenco dei tuoi acquisti e monitora lo stato delle
                consegne.</p>
        </div>

        <!-- Statistiche Minimali e Selettore Pagina -->
        <div class="stats-summary-container">
            <div class="stats-group">
                <div class="flex flex-col gap-3 shrink-0">
                    <p class="text-[12px] font-black text-slate-500 uppercase tracking-widest">Totale Ordini</p>
                    <p class="text-3xl font-black text-white tracking-tighter">{{ $ordini->total() }}</p>
                </div>

                <div class="flex flex-col gap-3 pl-16 shrink-0">
                    <p class="text-[12px] font-black text-slate-500 uppercase tracking-widest">Stato Consegne</p>
                    <div class="relative flex items-center">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.6)] mr-3"></div>
                        <p class="text-lg font-bold text-emerald-400">Tutti consegnati</p>
                    </div>
                </div>
            </div>

            <!-- Selettore Per Pagina -->
            <div class="per-page-container">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-2">Visualizza</span>
                <form action="{{ route('customer.orders') }}" method="GET" id="perPageForm">
                    <select name="per_page" onchange="this.form.submit()"
                        class="bg-slate-800 border-0 rounded-xl text-xs font-bold text-white focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer py-1.5 pr-8 pl-3">
                        @foreach([10, 20, 50, 100] as $val)
                            <option value="{{ $val }}" {{ ($perPage ?? 10) == $val ? 'selected' : '' }}>{{ $val }} ordini</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Lista Ordini -->
        @if($ordini->count() > 0)
            <div class="overflow-hidden">
                <div class="divide-y divide-slate-800/60">
                    @foreach($ordini as $ordine)
                        <div class="order-row group">

                            <!-- Gruppo 1: ID e Data -->
                            <div class="order-id-group">
                                <div class="order-icon-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 11-8 0m-4 4v2m0 0l-5.586-5.586a1 1 0 00-1.414 0L4 12m0 0L3.293 12.707a1 1 0 01-1.414 0l-7-7a1 1 0 010-1.414l7-7a1 1 0 011.414 0L4 12z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white mb-1 group-hover:text-indigo-400 transition">Ordine
                                        #{{ $ordine->IDOrdineVendita }}</p>
                                    <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($ordine->Data)->format('d F Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Gruppo 2: Stato (Sempre Centrato) -->
                            <div class="sm:w-1/4 flex justify-center order-first sm:order-none">
                                <span class="status-badge-delivered">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Consegnato
                                </span>
                            </div>

                            <!-- Gruppo 3: Prezzo e Azione -->
                            <div class="order-price-group">
                                <p class="text-lg font-black text-white tracking-tighter">
                                    €{{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2) }}
                                </p>
                                <a href="#"
                                    class="px-5 py-2 rounded-xl bg-slate-800/60 text-slate-400 hover:bg-indigo-600 hover:text-white text-xs font-bold transition-all duration-300">
                                    Dettagli
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Paginazione Centrata in Colonna -->
                @if($ordini->hasPages() || $ordini->total() > 0)
                    <div class="pagination-centered-column">
                        <p class="pagination-label">
                            Visualizzazione da {{ $ordini->firstItem() }} a {{ $ordini->lastItem() }} di {{ $ordini->total() }}
                            risultati
                        </p>
                        <div class="premium-pagination">
                            {{ $ordini->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Stato Vuoto -->
            <div class="py-20 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-800/50 flex items-center justify-center text-slate-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Nessun ordine trovato</h3>
                <p class="text-slate-400 text-sm max-w-sm mb-8">Non hai ancora effettuato acquisti. Il tuo storico ordini
                    apparirà qui.</p>
                <a href="{{ route('catalog.index') }}"
                    class="px-10 py-4 rounded-2xl bg-indigo-600 text-white font-black text-base uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                    Vai al Catalogo
                </a>
            </div>
        @endif
    </div>
@endsection