@extends('layouts.dashboard')

@section('title', 'I Miei Ordini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders-list.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-4 sm:px-6 lg:px-8 animate-fade-in">

        <!-- Intestazione -->
        <div class="mb-10">
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-2">Storico Ordini</h2>
            <p class="text-slate-400 text-sm font-medium">Consulta l'elenco dei tuoi acquisti e monitora lo stato delle consegne.</p>
        </div>

        <!-- Statistiche Minimali e Selettore Pagina -->
        <div class="stats-summary-container mb-8 pb-6 border-b border-slate-800/40">
            <div class="stats-group">
                <div class="flex flex-col gap-1 shrink-0">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Totale Ordini</p>
                    <p class="text-2xl font-black text-white tracking-tighter">{{ $ordini->total() }}</p>
                </div>

                <div class="flex flex-col gap-1 shrink-0">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Stato Consegne</p>
                    <div class="relative flex items-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] mr-2"></div>
                        <p class="text-sm font-bold text-emerald-400">Tutti consegnati</p>
                    </div>
                </div>
            </div>

            <!-- Selettore Per Pagina -->
            <div class="per-page-container">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest pl-1">Ordini per pagina</span>
                <form action="{{ route('customer.orders') }}" method="GET" id="perPageForm">
                    <select name="per_page" onchange="this.form.submit()"
                        class="bg-slate-900/50 border border-slate-800 rounded-xl text-[11px] font-black text-white focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer py-1 pr-8 pl-3">
                        @foreach([10, 20, 50, 100] as $val)
                            <option value="{{ $val }}" {{ ($perPage ?? 10) == $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Lista Ordini -->
        @if($ordini->count() > 0)
            <div class="overflow-hidden">
                <div class="divide-y divide-slate-800/40">
                    @foreach($ordini as $ordine)
                        <a href="{{ route('customer.orders.show', $ordine->IDOrdineVendita) }}" class="flex flex-col sm:flex-row sm:items-center justify-between p-6 hover:bg-indigo-500/5 transition-all group rounded-2xl mb-2">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-slate-900/50 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                    📦
                                </div>
                                <div>
                                    <p class="text-sm font-black text-white group-hover:text-indigo-400 transition">Ordine #{{ $ordine->IDOrdineVendita }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($ordine->Data)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-8 mt-4 sm:mt-0">
                                <div class="flex items-center gap-2 px-3 py-1 bg-slate-900/50 rounded-full border border-slate-800">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ordine->StatoConsegna == 'Consegnato' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]' : 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]' }}"></span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $ordine->StatoConsegna }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest mb-0.5">Totale</p>
                                    <p class="text-lg font-black text-white tracking-tighter">€{{ number_format($ordine->totale_ordine, 2, ',', '.') }}</p>
                                </div>
                                <div class="hidden sm:block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
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