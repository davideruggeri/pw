@extends('layouts.dashboard')

@section('title', 'Catalogo Prodotti')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="animate-fade-in">
        @if(session('success'))
            <div class="max-w-7xl mx-auto mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-sm font-bold text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-bold text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header e Filtri Integrati -->
        <div class="filter-bar scrollbar-hide" x-data="{ searchOpen: {{ request('search') ? 'true' : 'false' }} }">
            <a href="{{ route('catalog.index') }}"
                class="filter-btn {{ !request('category') ? 'filter-btn-active' : '' }}">
                Tutti i Prodotti
            </a>
            
            @foreach($categories as $category)
                <a href="{{ route('catalog.index', ['category' => $category->IDCategoria]) }}"
                    class="filter-btn {{ request('category') == $category->IDCategoria ? 'filter-btn-active' : '' }}">
                    {{ $category->NomeCategoria }}
                </a>
            @endforeach

            <!-- Ricerca Integrata -->
            <div class="flex items-center gap-2">
                <button @click="searchOpen = !searchOpen" 
                    class="w-10 h-10 flex items-center justify-center rounded-2xl transition-all shadow-sm {{ request('search') ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                
                <div x-show="searchOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 w-0"
                     x-transition:enter-end="opacity-100 w-48 md:w-64"
                     class="glass-card flex items-center px-4 py-2 bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden h-10">
                    <form action="{{ route('catalog.index') }}" method="GET" class="w-full">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <input type="text" name="search" placeholder="Cerca..." value="{{ request('search') }}"
                            class="bg-transparent border-0 focus:ring-0 text-sm text-slate-600 dark:text-slate-300 placeholder-slate-400 w-full p-0">
                    </form>
                </div>
            </div>
        </div>

        <!-- Grid Prodotti -->
        <div class="catalog-grid">
            @forelse($products as $product)
                <div class="product-card group">

                    <!-- Immagine -->
                    <div class="product-image-container">
                        <span class="product-emoji">📦</span>
                        @if($product->QuantitaGiacenza <= $product->ScortaMinima)
                            <span class="stock-badge">Scorte Limitate</span>
                        @endif
                    </div>

                    <!-- Contenuto -->
                    <div class="product-info">
                        <div class="flex justify-between items-start mb-3">
                            <span class="category-label">
                                {{ $product->categoria->NomeCategoria ?? 'Generico' }}
                            </span>
                            <span class="text-xs text-slate-400 font-mono">#{{ $product->CodiceUnivoco }}</span>
                        </div>

                        <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2 group-hover:text-indigo-600 transition truncate">
                            {{ $product->Descrizione }}
                        </h4>

                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4 flex-1">
                            Codice Prodotto: {{ $product->CodiceUnivoco }}
                        </p>

                        <div class="mt-auto pt-5 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase tracking-tighter font-black">Prezzo di Vendita</span>
                                <span class="product-price">€{{ number_format($product->PrezzoVendita, 2) }}</span>
                            </div>

                            <a href="{{ route('catalog.show', $product->CodiceUnivoco) }}"
                                class="w-12 h-12 rounded-2xl bg-slate-900 dark:bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:scale-110 transition-all shadow-xl z-20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Bottoni di interazione (Top Right) -->
                    <div class="absolute top-4 right-4 z-50 flex flex-row-reverse gap-2">
                        @auth
                            @if(auth()->user()->isCustomer())
                                <form action="{{ route('cart.add', $product->CodiceUnivoco) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-10 h-10 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all text-slate-400 hover:text-indigo-600"
                                        title="Aggiungi al carrello">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </form>

                                <form action="{{ route('catalog.favorite', trim($product->CodiceUnivoco)) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-10 h-10 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all {{ in_array(trim($product->CodiceUnivoco), $favoriteIds ?? []) ? 'text-red-500' : 'text-slate-400 hover:text-red-500' }}"
                                        style="{{ in_array(trim($product->CodiceUnivoco), $favoriteIds ?? []) ? 'color: #ef4444 !important;' : '' }}"
                                        title="Aggiungi ai preferiti">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-400">Nessun prodotto trovato in questa categoria.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginazione -->
        <div class="mt-16 pt-8 border-t border-slate-800/40 flex justify-center">
            <div class="premium-pagination">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection