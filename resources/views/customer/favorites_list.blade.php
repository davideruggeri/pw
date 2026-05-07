@extends('layouts.dashboard')

@section('title', 'I Miei Preferiti')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-4 sm:px-6 lg:px-8 animate-fade-in">

        <!-- Intestazione -->
        <div class="mb-16 pb-12">
            <h2 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-6">I Tuoi Preferiti</h2>
            <p class="text-slate-400 text-lg leading-relaxed">I prodotti che hai salvato per non perderteli. Aggiungili al
                carrello quando vuoi.</p>
        </div>

        <!-- Statistiche Minimali -->
        <div class="stats-summary-container">
            <div class="stats-group">
                <div class="flex flex-col gap-3 shrink-0">
                    <p class="text-[12px] font-black text-slate-500 uppercase tracking-widest">Prodotti salvati</p>
                    <p class="text-3xl font-black text-white tracking-tighter">{{ $preferiti->total() }}</p>
                </div>

                <div class="flex flex-col gap-3 pl-16  shrink-0">
                    <p class="text-[12px] font-black text-slate-500 uppercase tracking-widest">Disponibilità</p>
                    <div class="relative flex items-center">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.6)] mr-3"></div>
                        <p class="text-lg font-bold text-emerald-400">In magazzino</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista dei prodotti salvati: iteriamo sulla variabile paginata $preferiti -->
        @if($preferiti->count() > 0)
            <div class="overflow-hidden">
                <div class="divide-y divide-slate-800/60">
                    @foreach($preferiti as $prodotto)
                        <div class="favorites-row group">

                            <!-- Gruppo 1: Immagine segnaposto e dettagli (Nome + Categoria tramite relazione) -->
                            <div class="favorite-id-group">
                                <div class="favorite-icon-box">
                                    📦
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">
                                        {{ $prodotto->categoria->NomeCategoria ?? 'Generico' }}
                                    </p>
                                    <h4
                                        class="text-lg font-bold text-white truncate leading-tight group-hover:text-indigo-400 transition">
                                        {{ $prodotto->Descrizione }}
                                    </h4>
                                </div>
                            </div>

                            <!-- Gruppo 2: Prezzo -->
                            <div class="sm:w-1/4 flex justify-start sm:justify-center">
                                <p class="favorite-price">
                                    €{{ number_format($prodotto->PrezzoVendita, 2) }}
                                </p>
                            </div>

                            <!-- Gruppo 3: Azioni rapide (Rimuovi o Visualizza prodotto) -->
                            <div class="favorite-actions">
                                <form action="{{ route('catalog.favorite', $prodotto->CodiceUnivoco) }}" method="POST">
                                    @csrf
                                    <!-- Il tasto cuore rimuove il prodotto se già presente (logica toggle nel controller) -->
                                    <button type="submit" class="heart-button" title="Rimuovi dai preferiti">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                                <a href="{{ route('catalog.show', $prodotto->CodiceUnivoco) }}" class="view-btn">
                                    Vedi
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>

                @if($preferiti->hasPages())
                    <div class="pagination-centered-column">
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