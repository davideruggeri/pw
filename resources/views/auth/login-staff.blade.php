@extends('layouts.app')

@section('title', 'Accesso Staff')

@section('content')
    <div class="login-wrapper">
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

    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
            padding: 1rem;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 850px;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Colonna Info */
        .login-info {
            flex: 1;
            padding: 2.5rem;
            background: rgba(168, 85, 247, 0.05);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }

        .login-info .icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .login-info h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .login-info p {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .login-tip.staff {
            background: rgba(168, 85, 247, 0.1);
            border: 1px solid rgba(168, 85, 247, 0.2);
            padding: 1rem;
            border-radius: 16px;
        }

        .login-tip p {
            color: #c084fc;
            font-size: 0.85rem;
            margin: 0;
        }

        .login-tip code {
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
            color: white;
            font-weight: 700;
        }

        /* Colonna Form */
        .login-form {
            flex: 1.2;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            letter-spacing: 0.05em;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 0.85rem 1rem;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        input:focus {
            border-color: #a855f7;
            background: rgba(0, 0, 0, 0.4);
            outline: none;
        }

        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-size: 1.1rem;
            opacity: 0.5;
        }

        .toggle-password:hover {
            opacity: 1;
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-login.accent {
            background: linear-gradient(135deg, #a855f7, #ec4899);
            color: white;
            box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
        }

        .login-footer a {
            color: #475569;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: #c084fc;
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.7rem;
            margin-top: 0.3rem;
            display: block;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
            }

            .login-info {
                padding: 1.5rem;
                text-align: center;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .login-info .icon {
                font-size: 2.5rem;
            }

            .login-info p {
                margin-bottom: 1rem;
            }

            .login-form {
                padding: 1.5rem;
            }
        }
    </style>
@endsection