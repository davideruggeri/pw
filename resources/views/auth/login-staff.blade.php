@extends('layouts.app')

@section('title', 'Accesso Staff')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/auth-pages.css') }}">
    <div class="login-wrapper staff">
        <div class="login-container glass animate-fade-in">
            <!-- Colonna Sinistra: Info -->
            <div class="login-info">
                <div class="icon">🏢</div>
                <h1>Area Personale Staff</h1>
                <p>Accesso riservato ai dipendenti per la gestione aziendale.</p>

                <div class="login-tip staff">
                    <p>💡 <strong>Primo Accesso?</strong><br>Usa la password base: <code>Benvenuto2026!</code></p>
                </div>
            </div>

            <!-- Colonna Destra: Form -->
            <div class="login-form">
                <form method="POST" action="{{ route('login') }}" x-data="{ show: false }">
                    @csrf

                    <div class="form-group">
                        <label for="login">Email o Matricola</label>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus
                            placeholder="Inserisci credenziali">
                        @error('login') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-input-wrapper">
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="toggle-password">
                                <span x-show="!show">👁️</span>
                                <span x-show="show" style="display: none;">🔒</span>
                            </button>
                        </div>
                        @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn-login accent">Accedi al Sistema</button>

                    <div class="login-footer">
                        <a href="{{ route('home') }}">Torna alla Prelobby</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection