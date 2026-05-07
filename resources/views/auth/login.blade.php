@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 px-4">
        <div class="w-full max-w-md animate-fade-in">
            <!-- Logo/Title -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white tracking-tight">
                    Gestionale
                </h1>
                <p class="text-slate-400 mt-2">Accedi per gestire la tua azienda</p>
            </div>

            <!-- Login Card -->
            <div class="glass-card p-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email o Matricola -->
                    <div class="mb-6">
                        <label for="login" class="block text-sm font-medium text-slate-300 mb-2">Email o Matricola</label>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus
                            class="w-full input-premium @error('login') border-red-500 @enderror"
                            placeholder="es: m.rossi@azienda.it o 100010">
                        @error('login')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                            <a href="#" class="text-xs text-indigo-400 hover:text-indigo-300 transition">Dimenticata?</a>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full input-premium @error('password') border-red-500 @enderror" placeholder="••••••••">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-6">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-slate-700 bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 text-sm text-slate-400">Resta collegato</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-premium justify-center text-lg">
                        <span>Entra nel sistema</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-slate-500 text-xs mt-8">
                &copy; {{ date('Y') }} Gestionale Magazzino. Tutti i diritti riservati.
            </p>
        </div>
    </div>

    <style>
        /* Override per adattarsi al tema dark del login */
        .glass-card {
            background: rgba(30, 41, 59, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .input-premium {
            background: rgba(15, 23, 42, 0.5) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .input-premium::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
    </style>
@endsection