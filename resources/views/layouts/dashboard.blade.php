<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - {{ config('app.name', 'Gestionale') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}?v={{ time() }}">
    @stack('styles')
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Force Dark Mode Globally -->
    <script>
        document.documentElement.classList.add('dark');
        document.addEventListener('DOMContentLoaded', () => {
            if (document.body) document.body.classList.add('dark');
        });
        localStorage.setItem('dark-mode', 'enabled');
    </script>

    <script>
        window.notify = (message, type = 'success') => {
            window.dispatchEvent(new CustomEvent('notify', { detail: { message, type } }));
        };
    </script>
</head>

<body class="antialiased bg-slate-50 dark:bg-slate-950 text-black dark:text-white" x-data="{ sidebarOpen: window.innerWidth > 1024 }">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside 
            x-cloak
            :class="sidebarOpen ? 'sidebar-container bg-white dark:bg-slate-900' : 'sidebar-container sidebar-closed bg-white dark:bg-slate-900'">
            
            <!-- Header Sidebar -->
            <div class="p-8 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-2xl font-black text-indigo-600 tracking-tighter">Gestionale</h2>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-black flex items-center gap-2">
                        Workspace v2
                        @if(Auth::check())
                            @if(Auth::user()->isAdmin())
                                <span class="bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded text-[8px]">ADMIN</span>
                            @elseif(Auth::user()->isManager())
                                <span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded text-[8px]">MANAGER</span>
                            @endif
                        @endif
                    </p>
                </div>
                <button @click.stop="sidebarOpen = false" class="p-2 text-slate-300 hover:text-indigo-600 transition lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Navigazione -->
            <nav class="nav-container">
                <!-- Sezione Generale -->
                <div class="nav-section-title">Generale</div>
                
                @php
                    $role = Auth::user()->effective_role ?? 'customer';
                    $dashRoute = in_array($role, ['logistics']) ? $role . '.index' : ($role === 'admin' ? 'admin.dashboard' : $role . '.dashboard');
                @endphp
                <a href="{{ Auth::check() ? route($dashRoute) : route('home') }}"
                    class="nav-link {{ request()->routeIs($dashRoute) ? 'nav-link-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                @if(!Auth::check() || (Auth::check() && Auth::user()->isCustomer()))
                <a href="{{ route('catalog.index') }}" class="nav-link {{ request()->routeIs('catalog.index') ? 'nav-link-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Esplora Catalogo</span>
                </a>

                <a href="{{ route('customer.cart') }}" class="nav-link {{ request()->routeIs('customer.cart') ? 'nav-link-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="flex-1 text-left">Carrello</span>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span id="cart-count-badge" class="bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 px-2 py-0.5 rounded-full text-[10px] font-black">{{ $cartCount }}</span>
                    @else
                        <span id="cart-count-badge" class="hidden bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 px-2 py-0.5 rounded-full text-[10px] font-black">0</span>
                    @endif
                </a>
                @endif



                @if(Auth::check())
                    <!-- Sezione Amministrazione (Admin e Manager) -->
                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                        <div class="nav-section-title">HR & Admin</div>
                        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'nav-link-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Dipendenti</span>
                        </a>
                        <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'nav-link-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Reparti</span>
                        </a>
                        <!-- Link Magazzino -->
                        <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'nav-link-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Magazzino</span>
                        </a>
                    @endif

                    <!-- Reparti Operativi (Produzione, Manutenzione, Qualità) -->


                    <!-- Sezione Operativa -->
                    @php 
                        $isStaff = Auth::user()->isAdmin() || Auth::user()->isManager() || Auth::user()->isSales() || Auth::user()->role === 'logistics'; 
                    @endphp
                    
                    @if(Auth::user()->isStaff() || Auth::user()->isAdmin())
                        <div class="nav-section-title">Operazioni</div>
                        

                        
                        @if(Auth::user()->isAdmin() || Auth::user()->isSales())
                            <a href="{{ route('orders.pending') }}" class="nav-link {{ request()->routeIs('orders.pending') ? 'nav-link-active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Approvazione Ordini</span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') || request()->routeIs('orders.show') ? 'nav-link-active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span>Archivio Vendite</span>
                            </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->role === 'logistics')
                            <a href="{{ route('logistics.inventory') }}" class="nav-link {{ request()->routeIs('logistics.inventory') ? 'nav-link-active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                <span>Inventario</span>
                            </a>
                            <a href="{{ route('logistics.replenishment') }}" class="nav-link {{ request()->routeIs('logistics.replenishment') ? 'nav-link-active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                <span>Rifornimento</span>
                            </a>
                            <a href="{{ route('logistics.replenishment-history') }}" class="nav-link {{ request()->routeIs('logistics.replenishment-history') ? 'nav-link-active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                <span>Storico Rifornimenti</span>
                            </a>
                            <a href="{{ route('logistics.update') }}" class="nav-link {{ request()->routeIs('logistics.update') ? 'nav-link-active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                                <span>Carico/Scarico</span>
                            </a>
                        @endif
                    @endif

                    @if(Auth::check() && Auth::user()->isCustomer())
                    <div class="nav-section-title">Area Personale</div>
                    <a href="{{ route('customer.orders') }}" class="nav-link {{ request()->routeIs('customer.orders') ? 'nav-link-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <span class="flex-1 text-left">I miei Ordini</span>
                        @php $orderCount = Auth::check() && Auth::user()->cliente ? Auth::user()->cliente->ordiniVendita()->count() : 0; @endphp
                        @if($orderCount > 0)
                            <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2 py-0.5 rounded-full text-[10px] font-black">{{ $orderCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('customer.favorites') }}" class="nav-link {{ request()->routeIs('customer.favorites') ? 'nav-link-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="flex-1 text-left">Preferiti</span>
                        @php $favCount = Auth::check() && Auth::user()->cliente ? Auth::user()->cliente->preferiti()->count() : 0; @endphp
                        @if($favCount > 0)
                            <span id="fav-count-badge" class="bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 px-2 py-0.5 rounded-full text-[10px] font-black">{{ $favCount }}</span>
                        @else
                            <span id="fav-count-badge" class="hidden bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 px-2 py-0.5 rounded-full text-[10px] font-black">0</span>
                        @endif
                    </a>
                    @endif

                    <!-- Role Switcher (Solo per SuperAdmin) -->
                    @if(Auth::check() && Auth::user()->email === 'admin@azienda.it')
                        <div class="nav-section-title text-amber-500">Cambia Ruolo (Debug)</div>
                        <div class="px-4 pb-4">
                            <form action="" method="POST" id="roleSwitcherForm">
                                @csrf
                                <select onchange="this.form.action='/debug/switch-role/' + this.value; this.form.submit()" 
                                        class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-xs rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-3 font-bold cursor-pointer hover:bg-slate-100 transition-all">
                                    <option value="" disabled selected>Seleziona Ruolo...</option>
                                    @foreach(['admin', 'sales', 'logistics', 'customer'] as $role)
                                        <option value="{{ $role }}" {{ Auth::user()->role === $role ? 'selected' : '' }}>
                                            {{ strtoupper($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    @endif
                @endif

                <!-- Spacer per spingere il Logout in fondo -->
                <div class="flex-1"></div>
                <div class="sidebar-footer-area">
                    <!-- Info Utente Sidebar -->
                    @if(Auth::check() && !Auth::user()->isCustomer())
                        <div class="px-8 py-5 mx-4 bg-slate-50/50 dark:bg-black/20 rounded-2xl shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 shrink-0 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-lg font-black shadow-lg shadow-indigo-500/20">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-black dark:text-white uppercase tracking-wider leading-tight">
                                        {{ match(Auth::user()->effective_role) {
                                            'admin' => 'Amministratore',

                                            'logistics' => 'Addetto Logistica',
                                            'sales' => 'Commerciale',
                                            'manager' => 'Responsabile',
                                            default => 'Staff'
                                        } }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <header class="h-24 flex items-center justify-between px-10 bg-transparent">
                <div class="flex items-center gap-6">
                    <button x-cloak x-show="!sidebarOpen" @click.stop="sidebarOpen = true" class="p-3 rounded-[1.5rem] bg-white border border-slate-200 text-indigo-600 shadow-xl shadow-slate-200/50 hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-2xl font-black text-black dark:text-white tracking-tighter">@yield('title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Centro Notifiche -->
                    @if(Auth::check())
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="header-icon-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="notification-badge-container">
                                  <span class="notification-badge-ping"></span>
                                  <span class="notification-badge-number">{{ Auth::user()->unreadNotifications->count() }}</span>
                                </span>
                            @endif
                        </button>
                        
                        <!-- Menu Dropdown -->
                        <div x-show="open" x-transition x-cloak class="notification-dropdown">
                            <div class="notification-header">
                                <h3>Notifiche</h3>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.readAll') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="notification-mark-read">Segna lette</button>
                                </form>
                                @endif
                            </div>
                            <div class="notification-list">
                                @forelse(Auth::user()->unreadNotifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="notification-item">
                                        <div class="notification-icon-box">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="notification-title">{{ $notification->data['messaggio'] ?? 'Nuova Notifica' }}</p>
                                            <p class="notification-time">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="notification-empty">
                                        Nessuna notifica.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @else
                    <button class="header-icon-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                    @endif
                    <a href="{{ route('account.index') }}" class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white text-lg font-black shadow-lg shadow-indigo-500/20 hover:rotate-3 transition-all">
                        {{ Auth::check() ? substr(Auth::user()->name, 0, 1) : 'O' }}
                    </a>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto px-10 pb-10">
                @yield('content')
            </div>
        </main>
    </div>    <!-- Global Notifications System (Toasts) -->
    <div x-data="{ 
            notifications: [], 
            add(message, type) { 
                const id = Date.now();
                this.notifications.push({ id, message, type }); 
                setTimeout(() => this.remove(id), type === 'success' ? 3000 : 5000);
            },
            remove(id) { 
                this.notifications = this.notifications.filter(n => n.id !== id); 
            }
         }"
         @notify.window="add($event.detail.message, $event.detail.type)"
         class="fixed top-24 left-1/2 -translate-x-1/2 z-[999999] flex flex-col gap-3 pointer-events-none w-full max-w-md px-4">
        
        <!-- Laravel Flash Messages -->
        @if(session('success'))
             <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 -translate-y-10"
                  x-transition:enter-end="opacity-100 translate-y-0"
                  x-transition:leave="transition ease-in duration-300"
                  x-transition:leave-start="opacity-100 translate-y-0"
                  x-transition:leave-end="opacity-0 -translate-y-10"
                  class="pointer-events-auto bg-indigo-600 text-white px-6 py-4 rounded-2xl shadow-2xl shadow-indigo-500/40 flex items-center gap-4 min-w-[320px] mx-auto border border-white/10">
                <div class="bg-white/20 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] uppercase font-black tracking-widest opacity-70">Notifica</p>
                    <p class="font-bold tracking-tight text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
             <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 -translate-y-10"
                  x-transition:enter-end="opacity-100 translate-y-0"
                  x-transition:leave="transition ease-in duration-300"
                  x-transition:leave-start="opacity-100 translate-y-0"
                  x-transition:leave-end="opacity-0 -translate-y-10"
                  class="pointer-events-auto bg-rose-500 text-white px-6 py-4 rounded-2xl shadow-2xl shadow-rose-500/40 flex items-center gap-4 min-w-[320px] mx-auto border border-white/10">
                <div class="bg-white/20 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] uppercase font-black tracking-widest opacity-70">Errore</p>
                    <p class="font-bold tracking-tight text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Dynamic JS Notifications -->
        <template x-for="note in notifications" :key="note.id">
             <div x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 -translate-y-10"
                  x-transition:enter-end="opacity-100 translate-y-0"
                  x-transition:leave="transition ease-in duration-300"
                  x-transition:leave-start="opacity-100 translate-y-0"
                  x-transition:leave-end="opacity-0 -translate-y-10"
                  :class="note.type === 'success' ? 'bg-indigo-600 shadow-indigo-500/30' : 'bg-rose-500 shadow-rose-500/30'"
                  class="pointer-events-auto text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[320px] mx-auto">
                <div class="bg-white/20 p-2 rounded-xl">
                    <template x-if="note.type === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </template>
                    <template x-if="note.type === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </template>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] uppercase font-black tracking-widest opacity-70" x-text="note.type === 'success' ? 'Notifica' : 'Attenzione'"></p>
                    <p class="font-bold tracking-tight text-sm" x-text="note.message"></p>
                </div>
                <button @click="remove(note.id)" class="text-white/50 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </template>
    </div>
</body>
</html>