@extends('layouts.app')

@section('title', 'Registrazione Cliente')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 px-4">
    <div class="w-full max-w-lg animate-fade-in">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white tracking-tight">Diventa nostro Cliente</h1>
            <p class="text-slate-400 mt-2">Crea un account per gestire i tuoi ordini</p>
        </div>

        <div class="glass-card p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nome Completo / Ragione Sociale -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nome o Ragione Sociale</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full input-premium @error('name') border-red-500 @enderror" placeholder="Mario Rossi s.r.l.">
                    @error('name')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Indirizzo Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full input-premium @error('email') border-red-500 @enderror" placeholder="email@esempio.it">
                    @error('email')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Indirizzo Spedizione -->
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-slate-300 mb-2">Indirizzo di Spedizione</label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}" required
                        class="w-full input-premium @error('address') border-red-500 @enderror" placeholder="Via Roma 123, Milano (MI)">
                    @error('address')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full input-premium @error('password') border-red-500 @enderror" placeholder="••••••••">
                        @error('password')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Conferma Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full input-premium" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full btn-premium justify-center text-lg">
                    <span>Crea Account</span>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-400 mt-6">
            Hai già un account? <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300">Accedi qui</a>
        </p>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(30, 41, 59, 0.7) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 1.5rem;
    }
    .input-premium {
        background: rgba(15, 23, 42, 0.5) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        padding: 0.75rem;
        border-radius: 0.5rem;
    }
</style>
@endsection
