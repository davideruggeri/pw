@extends('layouts.dashboard')

@section('title', 'Dashboard Cliente')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer-dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="max-w-7xl mx-auto animate-fade-in pt-12 pb-12">

        <!-- Hero / Welcome Section -->
        <div class="hero-section mb-48">
            <div class="hero-gradient"></div>
            <div class="hero-glow-1"></div>
            <div class="hero-glow-2"></div>
            <div class="hero-content">
                <h2 class="hero-title">
                    {{ $user ? "Bentornato, $user->name! 👋" : "Benvenuto in Area Demo! 🚀" }}
                </h2>
                <p class="hero-subtitle">
                    {{ $user
        ? "Gestisci il tuo account in modo semplice. Accedi rapidamente ai tuoi ordini e ai tuoi prodotti preferiti direttamente dal menu laterale."
        : "Questa è un'anteprima dell'area clienti. Registrati per iniziare a salvare i tuoi prodotti preferiti e monitorare i tuoi acquisti in tempo reale." }}
                </p>
                @if(!$user)
                    <div class="flex flex-col sm:flex-row gap-5 w-full sm:w-auto justify-center mt-3 mb-6 relative z-20">
                        <a href="{{ route('register') }}"
                            class="px-10 py-4 bg-white text-slate-900 rounded-2xl font-black hover:scale-105 transition shadow-xl border border-slate-200">Crea
                            un Account</a>
                        <a href="{{ route('login.customer') }}"
                            class="px-10 py-4 bg-indigo-600 border border-indigo-400 text-white rounded-2xl font-black hover:bg-indigo-700 transition shadow-lg">Accedi</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Titolo Sezione Bestseller -->
        <div class="flex items-center justify-between mb-12 mt-32 px-2 bestseller-header">
            <div>
                <h3 class="text-3xl font-black text-black dark:text-white tracking-tighter">I Bestseller del Momento</h3>
                <p class="text-slate-600 dark:text-slate-400 mt-1 font-medium text-sm">I prodotti più popolari selezionati
                    per te.</p>
            </div>
            <span class="trending-badge">Trending</span>
        </div>

        <!-- Grid Bestseller -->
        <div class="bestseller-grid">
            @foreach($bestsellers as $prodotto)
                <div class="bestseller-card group">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="bestseller-image-box">
                            <span class="bestseller-emoji">📦</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span
                                class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest block mb-1">
                                {{ $prodotto->categoria->NomeCategoria ?? 'Prodotto' }}
                            </span>
                            <h4 class="font-bold text-base text-slate-900 dark:text-white leading-tight line-clamp-2">
                                {{ $prodotto->Descrizione }}
                            </h4>
                        </div>
                    </div>

                    <div
                        class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800/50 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Prezzo Listino</span>
                            <span class="text-lg font-black text-slate-900 dark:text-white tracking-tighter">
                                €{{ number_format($prodotto->PrezzoVendita, 2, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('catalog.show', $prodotto->CodiceUnivoco) }}"
                            class="flex items-center gap-2 px-3 py-1.5 bg-slate-900 dark:bg-indigo-600 text-white rounded-lg hover:bg-black dark:hover:bg-indigo-500 transition-all shadow-lg group/btn">
                            <span class="text-[9px] font-black uppercase tracking-widest">Dettagli</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if(auth()->check() && $recentOrders->count() > 0)
            <!-- Recent Orders Section -->
            <div class="mt-20">
                <div class="flex items-center justify-between mb-8 px-2">
                    <div>
                        <h3 class="text-3xl font-black text-black dark:text-white tracking-tighter">Ultimi Ordini Effettuati
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mt-1 font-medium text-sm">Monitora lo stato delle tue
                            ultime spedizioni.</p>
                    </div>
                    <a href="{{ route('customer.orders') }}"
                        class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline">Vedi
                        Tutti</a>
                </div>

                <div class="recent-orders-box shadow-sm animate-fade-in">
                    <div class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach($recentOrders as $ordine)
                            <div class="order-mini-row group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-lg">
                                        📦</div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 dark:text-white">Ordine
                                            #{{ $ordine->IDOrdineVendita }}</p>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                                            {{ date('d M Y', strtotime($ordine->Data)) }}</p>
                                    </div>
                                </div>

                                <div class="hidden md:flex items-center gap-8">
                                    <div class="text-right">
                                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Totale</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">
                                            €{{ number_format($ordine->TotaleOrdine, 2, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 px-3 py-1 bg-slate-50 dark:bg-slate-900 rounded-full">
                                        <span
                                            class="status-indicator {{ $ordine->StatoConsegna == 'Consegnato' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        <span
                                            class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest">{{ $ordine->StatoConsegna }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('customer.orders') }}"
                                    class="p-2 text-slate-300 hover:text-indigo-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection