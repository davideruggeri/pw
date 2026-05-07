@extends('layouts.dashboard')

@section('title', 'I Miei Preferiti')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-4 sm:px-6 lg:px-8 animate-fade-in">

        <!-- Intestazione -->
        <div class="mb-10">
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-2">I Tuoi Preferiti</h2>
            <p class="text-slate-400 text-sm font-medium">I prodotti che hai salvato per non perderteli. Aggiungili al carrello quando vuoi.</p>
        </div>

        <!-- Statistiche Minimali -->
        <div class="stats-summary-container mb-8 pb-6 border-b border-slate-800/40">
            <div class="stats-group">
                <div class="flex flex-col gap-1 shrink-0">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Prodotti salvati</p>
                    <p class="text-2xl font-black text-white tracking-tighter">{{ $preferiti->total() }}</p>
                </div>

                <div class="flex flex-col gap-1 shrink-0">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Disponibilità</p>
                    <div class="relative flex items-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] mr-2"></div>
                        <p class="text-sm font-bold text-emerald-400">In magazzino</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista dei prodotti salvati -->
        @if($preferiti->count() > 0)
            <div class="overflow-hidden">
                <div class="divide-y divide-slate-800/40">
                    @foreach($preferiti as $prodotto)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 hover:bg-indigo-500/5 transition-all group rounded-2xl mb-2">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-slate-900/50 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                    📦
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-0.5">{{ $prodotto->categoria->NomeCategoria ?? 'Generico' }}</p>
                                    <h4 class="text-sm font-black text-white group-hover:text-indigo-400 transition">{{ $prodotto->Descrizione }}</h4>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-8 mt-4 sm:mt-0">
                                <div class="text-right">
                                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest mb-0.5">Prezzo</p>
                                    <p class="text-lg font-black text-white tracking-tighter">€{{ number_format($prodotto->PrezzoVendita, 2, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <form action="{{ route('catalog.favorite', $prodotto->CodiceUnivoco) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-lg active:scale-90">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('catalog.show', $prodotto->CodiceUnivoco) }}" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-[11px] font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
                                        Dettagli
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($preferiti->hasPages() || $preferiti->total() > 0)
                    <div class="pagination-centered-column">
                        <p class="pagination-label">
                            Visualizzazione da {{ $preferiti->firstItem() }} a {{ $preferiti->lastItem() }} di {{ $preferiti->total() }} preferiti
                        </p>
                        <div class="premium-pagination">
                            {{ $preferiti->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Stato Vuoto -->
            <div class="py-24 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-800/50 flex items-center justify-center text-slate-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Ancora nessun preferito</h3>
                <p class="text-slate-400 text-sm max-w-sm mb-8">Salva i prodotti che ti piacciono per ritrovarli facilmente
                    in futuro.</p>
                <a href="{{ route('catalog.index') }}"
                    class="px-10 py-4 rounded-2xl bg-indigo-600 text-white font-black text-base uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                    Scopri il Catalogo
                </a>
            </div>
        @endif
    </div>
@endsection