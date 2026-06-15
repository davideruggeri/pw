@extends('layouts.dashboard')

@section('title', 'Magazzino - Inventario e Rifornimento')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
@endpush

@section('content')
    <div class="logistics-container" x-data="{ activeTab: '{{ request('tab') }}' || localStorage.getItem('logistics_inventory_tab') || 'table' }">

        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Magazzino</h3>
                <p class="text-slate-500 text-sm">Giacenze in tempo reale, monitoraggio sotto scorta e rifornimento scorte.</p>
            </div>
            
            <!-- Tab Switcher -->
            <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-2xl border border-slate-200 dark:border-slate-700/50 shadow-sm shrink-0">
                <button @click="activeTab = 'table'; localStorage.setItem('logistics_inventory_tab', 'table')"
                        :class="activeTab === 'table' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center gap-2 border-0 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Vista Tabella (Inventario)
                </button>
                <button @click="activeTab = 'replenishment'; localStorage.setItem('logistics_inventory_tab', 'replenishment')"
                        :class="activeTab === 'replenishment' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center gap-2 border-0 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Vista Card (Rifornimento)
                </button>
            </div>
        </div>

        <!-- KPI Cards Section -->
        <div class="kpi-grid">
            <!-- Totale Prodotti -->
            <div class="kpi-card">
                <div class="kpi-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Articoli Unici</p>
                    <p class="kpi-value">{{ number_format($totalProductsCount, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Valore Magazzino -->
            <div class="kpi-card">
                <div class="kpi-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Valore Totale Stock</p>
                    <p class="kpi-value">€ {{ number_format($totalStockValue, 2, ',', '.') }}</p>
                </div>
            </div>

            <!-- Prodotti Sotto Scorta -->
            <div class="kpi-card {{ $lowStockCount > 0 ? 'kpi-card-danger' : '' }}">
                <div class="kpi-icon-container {{ $lowStockCount > 0 ? 'kpi-icon-container-danger' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Allarmi Sotto Scorta</p>
                    <p class="kpi-value">{{ $lowStockCount }}</p>
                </div>
            </div>

            <!-- Salute Magazzino -->
            @php
                $health = $totalProductsCount > 0 ? round((($totalProductsCount - $lowStockCount) / $totalProductsCount) * 100) : 100;
            @endphp
            <div class="kpi-card kpi-card-success">
                <div class="kpi-icon-container kpi-icon-container-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Salute Magazzino</p>
                    <p class="kpi-value">{{ $health }}%</p>
                </div>
            </div>
        </div>

        <!-- Filtri e Ricerca -->
        <div class="logistics-filter-bar">
            <div class="filter-left-section">
                <!-- Ricerca -->
                <form action="{{ url()->current() }}" method="GET" class="flex-1 min-w-[280px]">
                    @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                    <div class="logistics-search-wrapper">
                        <div class="logistics-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cerca per descrizione o codice..." class="logistics-input logistics-input-with-icon">
                    </div>
                </form>

                <!-- Categoria Dropdown -->
                <form action="{{ url()->current() }}" method="GET" class="filter-group">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                    @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                    <label class="filter-label">Categoria</label>
                    <select name="category" onchange="this.form.submit()" class="filter-select">
                        <option value="">Tutte le Categorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->IDCategoria }}" {{ request('category') == $cat->IDCategoria ? 'selected' : '' }}>
                                {{ $cat->NomeCategoria }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Stato Scorta -->
                <div class="filter-group">
                    <label class="filter-label">Stato</label>
                    <div class="status-pills">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => null, 'page' => null]) }}" class="status-pill {{ !request('filter') ? 'status-pill-active' : '' }}">
                            Tutti
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'low_stock', 'page' => null]) }}" class="status-pill {{ request('filter') === 'low_stock' ? 'status-pill-active' : '' }}">
                            Sotto Scorta
                        </a>
                    </div>
                </div>
            </div>

            <!-- Paginazione Quantità -->
            <div class="per-page-container flex items-center bg-slate-100 dark:bg-slate-800/50 px-4 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700/50">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mr-2">Righe</span>
                <form action="{{ url()->current() }}" method="GET">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    <select name="per_page" onchange="this.form.submit()"
                        class="bg-transparent border-0 text-[11px] font-black text-slate-900 dark:text-white focus:ring-0 cursor-pointer py-0 pr-8 pl-0">
                        @foreach([10, 25, 50, 100] as $val)
                            <option value="{{ $val }}" {{ ($perPage ?? 10) == $val ? 'selected' : '' }}
                                class="bg-white dark:bg-slate-900">{{ $val }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Vista Tabella (Inventario Completo) -->
        <div x-show="activeTab === 'table'" x-cloak class="logistics-card">
            <div class="overflow-x-auto">
                <table class="logistics-table">
                    <thead>
                        <tr class="logistics-table-header">
                            <th class="logistics-table-th">Codice</th>
                            <th class="logistics-table-th">Prodotto</th>
                            <th class="logistics-table-th">Categoria</th>
                            <th class="logistics-table-th">Salute Stock</th>
                            <th class="logistics-table-th text-right">Giacenza</th>
                            <th class="logistics-table-th text-right">Valore Unitario</th>
                            <th class="logistics-table-th text-right">Valore Totale</th>
                            <th class="logistics-table-th text-center">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @forelse($products as $product)
                            <tr class="logistics-table-tr">
                                <td class="px-6 py-4 font-black text-xs text-indigo-500">#{{ $product->CodiceUnivoco }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-black text-slate-800 dark:text-white leading-none">
                                        {{ $product->NomeProdotto }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ Str::limit($product->Descrizione, 50) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $product->categoria->NomeCategoria ?? 'N/D' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $scorta = $product->ScortaMinima ?? 50;
                                        $ratio = $scorta > 0 ? min(($product->Giacenza / ($scorta * 2)) * 100, 100) : 100;
                                        $colorClass = $product->Giacenza < $scorta ? 'fill-critical' : ($product->Giacenza < $scorta * 1.5 ? 'fill-warning' : 'fill-optimal');
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="table-stock-progress-container">
                                            <div class="table-stock-progress-fill {{ $colorClass }}" style="width: {{ $ratio }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">
                                            {{ $scorta > 0 ? round(($product->Giacenza / $scorta) * 100) : 100 }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-block px-3 py-1 rounded-lg text-xs font-black {{ $product->Giacenza < ($product->ScortaMinima ?? 50) ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                                        {{ number_format($product->Giacenza, 0, ',', '.') }} {{ $product->UnitaMisura }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-slate-600 dark:text-slate-400">
                                    € {{ number_format($product->PrezzoListino, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-black text-slate-900 dark:text-white">
                                    € {{ number_format($product->Giacenza * $product->PrezzoListino, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(Auth::user()->isLogistics())
                                        <a href="{{ route('logistics.update', ['id' => $product->CodiceUnivoco]) }}" class="btn-action-movimenta">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4-4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                            <span>Movimenta</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Lettura</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-10 py-20 text-center text-slate-500">Nessun prodotto trovato.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista Card (Rifornimento Rapido) -->
        <div x-show="activeTab === 'replenishment'" x-cloak>
            <div class="replenishment-grid">
                @forelse($products as $product)
                    @php
                        $scorta = $product->ScortaMinima ?? 50;
                        $percentage = $scorta > 0 ? min(100, ($product->Giacenza / $scorta) * 100) : 0;
                        $statusClass = $product->Giacenza < $scorta ? 'fill-critical' : ($product->Giacenza < $scorta * 1.5 ? 'fill-warning' : 'fill-optimal');
                    @endphp
                    
                    <div class="replenishment-item-card group flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-950/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="flex flex-col items-end">
                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">#{{ $product->CodiceUnivoco }}</span>
                                 @if($product->Giacenza < $scorta)
                                    <span class="mt-1 px-2 py-0.5 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-[8px] font-black rounded-full uppercase tracking-tighter">Low Stock</span>
                                 @endif
                            </div>
                        </div>

                        <div class="mb-4 min-w-0">
                            <h4 class="text-sm font-black text-slate-800 dark:text-white leading-tight break-words line-clamp-2 group-hover:text-indigo-600 transition-colors" title="{{ $product->NomeProdotto }}">
                                {{ $product->NomeProdotto }}
                            </h4>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $product->categoria->NomeCategoria ?? 'Materiale' }}</p>
                        </div>

                        <div class="stock-progress-container mb-6">
                            <div class="flex justify-between items-end mb-2">
                                <div>
                                    <p class="text-[8px] font-black text-slate-400 uppercase">Giacenza</p>
                                    <p class="text-xs font-black text-slate-900 dark:text-white">{{ number_format($product->Giacenza, 0, ',', '.') }} <span class="text-[8px] opacity-60">{{ $product->UnitaMisura }}</span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[8px] font-black text-slate-400 uppercase">Min</p>
                                    <p class="text-xs font-black text-slate-500">{{ number_format($scorta, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="stock-progress-bar h-1.5">
                                <div class="stock-progress-fill {{ $statusClass }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>

                        @if(Auth::user()->isLogistics())
                            <a href="{{ route('logistics.update', ['id' => $product->CodiceUnivoco]) }}"
                                class="mt-auto w-full flex justify-center items-center gap-2 bg-slate-900 dark:bg-white/10 text-white dark:text-white py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 dark:hover:bg-indigo-600 transition-all border border-transparent">
                                <span>Rifornisci</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        @else
                            <div class="mt-auto w-full text-center border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest">
                                Sola Lettura
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-lg font-bold text-slate-400">Nessun prodotto trovato con i filtri selezionati.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Paginazione Premium -->
        @if($products->hasPages() || $products->total() > 0)
            <div class="pagination-centered-column mt-8">
                <p class="pagination-label">
                    Visualizzazione da {{ $products->firstItem() }} a {{ $products->lastItem() }} di {{ $products->total() }}
                    prodotti
                </p>
                <div class="premium-pagination">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        @endif

    </div>
@endsection