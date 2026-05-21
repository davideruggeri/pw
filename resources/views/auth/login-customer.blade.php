@extends('layouts.app')

@section('title', 'Accesso Clienti')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/auth-pages.css') }}">
    <div class="login-wrapper">
        <div class="login-container glass animate-fade-in">
            <!-- Colonna Sinistra: Info -->
            <div class="login-info">
                <div class="icon">🛍️</div>
                <h1>Area Clienti</h1>
                <p>Accedi per gestire i tuoi ordini e sfogliare il catalogo.</p>
                
                <div class="login-tip">
                    <p>💡 <strong>Primo Accesso?</strong><br>Usa la password base: <code>password</code></p>
                </div>
            </div>

            <!-- Colonna Destra: Form -->
            <div class="login-form">
                <form method="POST" action="{{ route('login') }}" x-data="{ show: false }">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Indirizzo Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="email@esempio.com">
                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-input-wrapper">
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••">
                            <button type="button" @click="show = !show" class="toggle-password">
                                <span x-show="!show">👁️</span>
                                <span x-show="show" style="display: none;">🔒</span>
                            </button>
                        </div>
                        @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn-login primary">Entra nell'Area Clienti</button>

                    <div class="login-footer">
                        <a href="{{ route('home') }}">Torna alla Prelobby</a>
                        <a href="{{ route('register') }}">Crea Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection