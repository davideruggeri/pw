@extends('layouts.dashboard')

@section('title', 'Dettaglio Prodotto')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="animate-fade-in py-8">
    <!-- Back link -->
    <div class="mb-8">
        <a href="{{ route('catalog.index') }}" class="text-slate-500 hover:text-indigo-600 flex items-center gap-2 transition font-bold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Torna al Catalogo
        </a>
    </div>

    <div class="detail-card">
        <div class="detail-info-grid">
            <!-- Immagine Prodotto -->
            <div class="product-hero-image">
                📦
            </div>

            <!-- Info Prodotto -->
            <div class="flex flex-col">
                <div class="mb-8">
                    <span class="px-3 py-1 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                        {{ $product->categoria->NomeCategoria ?? 'Generico' }}
                    </span>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mt-6 leading-tight tracking-tighter">
                        {{ $product->Descrizione }}
                    </h1>
                    <p class="text-slate-400 mt-3 font-mono text-xs">SKU: {{ $product->CodiceUnivoco }}</p>
                </div>

                <!-- Price and Stock Bar -->
                <div class="price-stock-bar">
                    <div class="flex-1">
                        <span class="detail-label">Prezzo di Vendita</span>
                        <span class="detail-value-price">€{{ number_format($product->PrezzoVendita, 2) }}</span>
                    </div>
                    <div class="w-px h-12 bg-slate-100 dark:bg-slate-700 hidden sm:block"></div>
                    <div class="flex-1 pl-0 sm:pl-6">
                        <span class="detail-label">Disponibilità</span>
                        <span class="text-2xl font-black {{ $product->QuantitaGiacenza > $product->ScortaMinima ? 'text-emerald-500' : 'text-red-500' }}">
                            {{ $product->QuantitaGiacenza }} <span class="text-sm font-bold uppercase ml-1">pezzi</span>
                        </span>
                    </div>
                </div>

                <!-- Descrizione -->
                <div class="mb-10">
                    <h3 class="text-slate-800 dark:text-slate-200 font-black uppercase text-xs tracking-widest mb-4">Specifiche</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                        Questo prodotto fa parte della linea professionale <strong>{{ $product->categoria->NomeCategoria ?? 'Generale' }}</strong>. 
                        Costruito con materiali di alta qualità per garantire prestazioni costanti e durature nel tempo.
                    </p>
                </div>

                <!-- Azioni -->
                <div class="mt-auto flex gap-4 pt-6 border-t border-slate-50 dark:border-slate-800/50">
                    <form action="{{ route('cart.add', $product->CodiceUnivoco) }}" method="POST" class="flex-1 flex">
                        @csrf
                        <button type="submit" class="action-btn-main">
                            Aggiungi al Carrello
                        </button>
                    </form>
                    
                    <form action="{{ route('catalog.favorite', $product->CodiceUnivoco) }}" method="POST">
                        @csrf
                        <button type="submit" class="action-btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
