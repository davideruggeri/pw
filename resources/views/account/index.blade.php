@extends('layouts.app')

@section('title', 'Gestione Account')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="account-wrapper" x-data="{ activeTab: 'info' }">
        <div class="account-container glass animate-fade-in">

            <!-- Colonna Sinistra: Profilo Rapido -->
            <div class="account-sidebar">
                <div class="avatar">
                    {{ $user ? substr($user->name, 0, 1) : 'O' }}
                </div>
                <h2>{{ $user ? $user->name : 'Ospite' }}</h2>
                <p class="role-badge">{{ $user ? ucfirst($user->effective_role) : 'Visitatore' }}</p>
                @if($user && $user->role_level >= 2)
                    <div class="flex justify-center gap-1 mt-2">
                        @for($i = 0; $i < $user->role_level; $i++)
                            <span class="text-amber-400 text-sm">★</span>
                        @endfor
                    </div>
                @endif

                <nav class="sidebar-nav">
                    <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'active' : ''">
                        <span>👤</span> Profilo
                    </button>
                    @if($user)
                        <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'active' : ''">
                            <span>🔒</span> Sicurezza
                        </button>
                    @endif
                    <button @click="activeTab = 'interface'" :class="activeTab === 'interface' ? 'active' : ''">
                        <span>🎨</span> Interfaccia
                    </button>
                </nav>
                
                @if($user && $user->email === 'admin@azienda.it')
                    <div class="role-switcher-mini">
                        <p>Cambia Ruolo (Debug)</p>
                        <form action="" method="POST">
                            @csrf
                            <select onchange="this.form.action='/debug/switch-role/' + this.value; this.form.submit()">
                                @foreach(['admin', 'sales', 'logistics', 'production', 'customer'] as $role)
                                    <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                        {{ strtoupper($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif

                <div class="sidebar-footer">
                    <a href="{{ $backUrl ?? route('home') }}" class="btn-back-lobby">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                        </svg>
                        Torna alla Home
                    </a>
                </div>
            </div>

            <!-- Colonna Destra: Contenuto Dinamico -->
            <div class="account-main">
                <div class="main-header">
                    <h1 x-text="activeTab === 'info' ? 'Informazioni Account' : (activeTab === 'security' ? 'Sicurezza Account' : 'Interfaccia')"></h1>
                    <p x-text="activeTab === 'info' ? 'Visualizza i dettagli del tuo profilo aziendale.' : (activeTab === 'security' ? 'Aggiorna le tue credenziali di accesso.' : 'Personalizza la tua esperienza.')"></p>
                </div>

                @if(session('warning'))
                    <div class="alert-mini warning mb-4">{{ session('warning') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert-mini success mb-4">{{ session('success') }}</div>
                @endif

                <!-- Tab: Informazioni -->
                <div x-show="activeTab === 'info'" x-transition>
                    <div class="content-section full">
                        <h3>Dati Personali</h3>
                        @if($user)
                            <div class="data-grid">
                                <div class="data-item">
                                    <label>Nome Utente</label>
                                    <span>{{ $user->name }}</span>
                                </div>
                                <div class="data-item">
                                    <label>Email di Accesso</label>
                                    <span>{{ $user->email }}</span>
                                </div>
                                @if($user->matricola_fk)
                                    <div class="data-item">
                                        <label>Matricola</label>
                                        <span>{{ $user->matricola_fk }}</span>
                                    </div>
                                @endif
                                @if(isset($dipendente))
                                    <div class="data-item">
                                        <label>Reparto</label>
                                        <span>{{ $dipendente->reparto->NomeReparto ?? 'N/D' }}</span>
                                    </div>
                                    <div class="data-item">
                                        <label>Qualifica</label>
                                        <span>{{ $dipendente->ruolo->NomeRuolo ?? 'N/D' }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="guest-box">
                                <p>⚠️ Non sei autenticato.</p>
                                <div class="flex-actions">
                                    <a href="{{ route('login') }}" class="btn-sm primary">Accedi</a>
                                    <a href="{{ route('register') }}" class="btn-sm secondary">Registrati</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tab: Sicurezza -->
                <div x-show="activeTab === 'security'" x-transition style="display: none;">
                    <div class="content-section full">
                        <h3>Cambio Password</h3>
                        <form action="{{ route('account.update-password') }}" method="POST" class="compact-form"
                            x-data="{ show: false, showConf: false }">
                            @csrf
                            <div class="form-group">
                                <label>Nuova Password</label>
                                <div class="input-with-icon">
                                    <input :type="show ? 'text' : 'password'" name="password" required
                                        placeholder="Inserisci nuova password">
                                    <button type="button" @click="show = !show" class="toggle-eye">
                                        <span x-show="!show">👁️</span>
                                        <span x-show="show" style="display: none;">🔒</span>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="error-msg-mini">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Conferma Nuova Password</label>
                                <div class="input-with-icon">
                                    <input :type="showConf ? 'text' : 'password'" name="password_confirmation" required
                                        placeholder="Ripeti la password">
                                    <button type="button" @click="showConf = !showConf" class="toggle-eye">
                                        <span x-show="!showConf">👁️</span>
                                        <span x-show="showConf" style="display: none;">🔒</span>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn-save">Salva Modifiche</button>
                        </form>
                    </div>
                </div>

                <!-- Tab: Interfaccia -->
                <div x-show="activeTab === 'interface'" x-transition style="display: none;">
                    <div class="content-section full">
                        <h3>Personalizzazione Tema</h3>
                        <div class="guest-box">
                            <p>Dark Mode attiva e bloccata dal sistema.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection