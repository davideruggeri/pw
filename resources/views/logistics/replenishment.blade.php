@extends('layouts.dashboard')

@section('title', 'Logistica - Rifornimento Scorte')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
@endpush

@section('content')
    <div class="logistics-container">

        <div class="mb-10 flex justify-between items-end">
            <div>
                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Centro Rifornimento</h3>
                <p class="text-slate-500 text-sm">Gestisci il riacquisto delle materie prime e dei prodotti finiti.</p>
            </div>
        </div>

        <!-- KPI Cards Section -->
        <div class="kpi-grid">
            <!-- Totale Articoli -->
            <div class="kpi-card">
                <div class="kpi-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Articoli in Anagrafica</p>
                    <p class="kpi-value">{{ $totalProductsCount }}</p>
                </div>
            </div>

            <!-- Allarmi Sotto Scorta -->
            <div class="kpi-card {{ $lowStockCount > 0 ? 'kpi-card-danger' : '' }}">
                <div class="kpi-icon-container {{ $lowStockCount > 0 ? 'kpi-icon-container-danger' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Sotto Scorta Reali</p>
                    <p class="kpi-value">{{ $lowStockCount }}</p>
                </div>
            </div>

            <!-- Salute Stock -->
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

        <!-- Filtri -->
        <div class="logistics-filter-bar">
            <div class="filter-left-section">
                <!-- Ricerca -->
                <form action="{{ url()->current() }}" method="GET" class="flex-1 min-w-[280px]">
                    @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                    @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                    <div class="logistics-search-wrapper">
                        <div class="logistics-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cerca per codice o descrizione..." class="logistics-input logistics-input-with-icon">
                    </div>
                </form>

                <!-- Stato Scorta (Pills) -->
                <div class="filter-group">
                    <label class="filter-label">Stato</label>
                    <div class="status-pills">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => null, 'page' => null]) }}" class="status-pill {{ !request('filter') ? 'status-pill-active' : '' }}">
                            Tutti
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'low_stock', 'page' => null]) }}" class="status-pill {{ request('filter') === 'low_stock' ? 'status-pill-active' : '' }}">
                            Solo Sottoscorta
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
                    <select name="per_page" onchange="this.form.submit()" class="bg-transparent border-0 text-[11px] font-black text-slate-900 dark:text-white focus:ring-0 cursor-pointer py-0 pr-8 pl-0">
                        @foreach([15, 30, 60, 120] as $val)
                            <option value="{{ $val }}" {{ ($perPage ?? 15) == $val ? 'selected' : '' }} class="bg-white dark:bg-slate-900">{{ $val }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Visualizzazione a Card (Grid) -->
        <div class="replenishment-grid">
            @forelse($products as $product)
                @php
                    $percentage = $product->ScortaMinima > 0 ? min(100, ($product->Giacenza / $product->ScortaMinima) * 100) : 0;
                    $statusClass = $product->Giacenza < $product->ScortaMinima ? 'fill-critical' : ($product->Giacenza < $product->ScortaMinima * 1.5 ? 'fill-warning' : 'fill-optimal');
                @endphp
                
                <div class="replenishment-item-card group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-955/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="flex flex-col items-end">
                             <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">#{{ $product->CodiceUnivoco }}</span>
                             @if($product->Giacenza < $product->ScortaMinima)
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

                    <div class="stock-progress-container mb-4">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase">Giacenza</p>
                                <p class="text-xs font-black text-slate-900 dark:text-white">{{ number_format($product->Giacenza, 0, ',', '.') }} <span class="text-[8px] opacity-60">{{ $product->UnitaMisura }}</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] font-black text-slate-400 uppercase">Min</p>
                                <p class="text-xs font-black text-slate-500">{{ number_format($product->ScortaMinima, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="stock-progress-bar h-1.5">
                            <div class="stock-progress-fill {{ $statusClass }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <a href="{{ route('logistics.update', ['id' => $product->CodiceUnivoco]) }}"
                        class="mt-auto w-full flex justify-center items-center gap-2 bg-slate-900 dark:bg-white/10 text-white dark:text-white py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 dark:hover:bg-indigo-600 transition-all border border-transparent">
                        <span>Rifornisci</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-lg font-bold text-slate-400">Nessun prodotto trovato con i filtri selezionati.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginazione Premium -->
        @if($products->hasPages() || $products->total() > 0)
            <div class="pagination-centered-column mt-10">
                <p class="pagination-label">
                    Trovati {{ $products->total() }} prodotti
                </p>
                <div class="premium-pagination">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        @endif

    </div>
@endsection