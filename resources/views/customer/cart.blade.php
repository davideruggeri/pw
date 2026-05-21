@extends('layouts.dashboard')

@section('title', 'Il Mio Carrello')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-4 sm:px-6 lg:px-8 animate-fade-in">

        <!-- Intestazione -->
        <div class="mb-16">
            <h2 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-6">Il Tuo Carrello</h2>
            <p class="text-slate-400 text-lg leading-relaxed">Completa l'acquisto dei prodotti che hai selezionato. La spedizione è gratuita per i membri Premium.</p>
        </div>

        <!-- Sezione Carrello -->
        <div class="cart-grid">
            <!-- Lista Articoli -->
            <div class="cart-items-column space-y-4">
                @php $cart = session('cart', []); $total = 0; @endphp

                @forelse($cart as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <div class="cart-item-card group">
                        <div class="flex items-center gap-6">
                            <div class="item-image-box">
                                {{ $details['image'] }}
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg">{{ $details['name'] }}</h3>
                                <p class="text-slate-500 text-sm">Prezzo: €{{ number_format($details['price'], 2) }}</p>
                                <div class="mt-2">
                                    <span class="text-xs text-indigo-400 font-bold uppercase tracking-widest">Quantità: {{ $details['quantity'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-4">
                            <span class="item-price">€{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-red-500 transition text-xs font-bold uppercase tracking-widest">
                                    Rimuovi
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <!-- Stato Vuoto -->
                    <div class="pt-20 pb-20 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-slate-800/50 flex items-center justify-center text-slate-500 mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Il carrello è vuoto</h3>
                        <p class="text-slate-400 text-sm max-w-xs mb-8">Non hai ancora aggiunto nulla. Esplora i prodotti e riempi il carrello!</p>
                        <a href="{{ route('catalog.index') }}"
                            class="px-10 py-4 rounded-2xl bg-indigo-600 text-white font-black text-base uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                            Vai al Catalogo
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Riepilogo Laterale -->
            <div>
                <div class="summary-card">
                    <h4 class="text-xl font-bold text-white mb-8 tracking-tight">Riepilogo</h4>

                    <div class="space-y-4 mb-8">
                        <div class="summary-row">
                            <span class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Subtotale</span>
                            <span class="text-white font-bold tracking-tight">€{{ number_format($total ?? 0, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Spedizione</span>
                            <span class="text-emerald-400 font-bold tracking-tight italic text-xs">Inclusa</span>
                        </div>
                    </div>

                    <div class="summary-total-row">
                        <span class="text-white font-black text-lg">Totale</span>
                        <span class="total-price">€{{ number_format($total ?? 0, 2) }}</span>
                    </div>

                    @if(count($cart ?? []) > 0)
                        @auth
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="checkout-btn w-full">Procedi all'ordine</button>
                            </form>
                        @else
                            <div x-data="{ showAuthPrompt: false }">
                                <button x-show="!showAuthPrompt" @click="showAuthPrompt = true" class="checkout-btn">
                                    Procedi all'ordine
                                </button>
                                
                                <div x-show="showAuthPrompt" x-cloak 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="mt-6 p-6 bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-indigo-500/30 text-center shadow-2xl">
                                    <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-4">Finalizza il tuo Ordine</p>
                                    <p class="text-slate-300 text-sm mb-6 font-medium">Per procedere al pagamento e confermare l'ordine, è necessario effettuare l'accesso.</p>
                                    <div class="grid grid-cols-1 gap-3">
                                        <a href="{{ route('login.customer') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-500 transition shadow-lg shadow-indigo-600/20">Accedi ora</a>
                                        <div class="flex items-center gap-4 my-2">
                                            <div class="h-px bg-slate-800 flex-1"></div>
                                            <span class="text-[10px] text-slate-500 font-black uppercase">Oppure</span>
                                            <div class="h-px bg-slate-800 flex-1"></div>
                                        </div>
                                        <a href="{{ route('register') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white border border-white/10 rounded-xl font-bold text-sm transition">Crea un nuovo account</a>
                                    </div>
                                    <button @click="showAuthPrompt = false" class="mt-4 text-[10px] text-slate-500 hover:text-white uppercase font-black tracking-widest transition">Annulla</button>
                                </div>
                            </div>
                        @endauth
                    @else
                        <button disabled class="checkout-btn checkout-btn-disabled">Carrello Vuoto</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection