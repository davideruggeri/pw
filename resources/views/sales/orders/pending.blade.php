@extends('layouts.dashboard')

@section('title', 'Commerciale - Approvazione Ordini')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sales-orders.css') }}">

<div class="premium-page-container animate-fade-in">

    <div class="mb-10 flex justify-between items-end">
        <div>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Attesa Approvazione</h3>
            <p class="text-slate-500 text-sm">Revisione e validazione degli ordini inoltrati dai clienti.</p>
        </div>
    </div>

    <!-- KPI Cards Section -->
    <div class="kpi-grid">
        <!-- Totale Ordini in Coda -->
        <div class="kpi-card">
            <div class="kpi-icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="kpi-details">
                <p class="kpi-title">Ordini in Coda</p>
                <p class="kpi-value">{{ $totalPendingCount }}</p>
            </div>
        </div>

        <!-- Valore Totale in Coda -->
        <div class="kpi-card kpi-card-warning">
            <div class="kpi-icon-container kpi-icon-container-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="kpi-details">
                <p class="kpi-title">Valore Sospeso</p>
                <p class="kpi-value">€ {{ number_format($totalPendingValue, 2, ',', '.') }}</p>
            </div>
        </div>

        <!-- Ticket Massimo -->
        <div class="kpi-card kpi-card-success">
            <div class="kpi-icon-container kpi-icon-container-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div class="kpi-details">
                <p class="kpi-title">Ticket Massimo</p>
                <p class="kpi-value">€ {{ number_format($maxPendingValue, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Filtri e Ricerca -->
    <div class="logistics-filter-bar">
        <div class="filter-left-section">
            <!-- Cerca per Cliente o ID -->
            <form action="{{ url()->current() }}" method="GET" class="flex-1 min-w-[280px]">
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                <div class="logistics-search-wrapper">
                    <div class="logistics-search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cerca per cliente o ID ordine..." class="logistics-input logistics-input-with-icon">
                </div>
            </form>

            <!-- Ordinamento -->
            <form action="{{ url()->current() }}" method="GET" class="filter-group">
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                <label class="filter-label">Ordina Per</label>
                <select name="sort" onchange="this.form.submit()" class="filter-select">
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Data (Meno recenti prima)</option>
                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Data (Più recenti prima)</option>
                    <option value="value_desc" {{ request('sort') == 'value_desc' ? 'selected' : '' }}>Valore (Più alti prima)</option>
                    <option value="value_asc" {{ request('sort') == 'value_asc' ? 'selected' : '' }}>Valore (Meno cari prima)</option>
                </select>
            </form>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="approval-card p-20 text-center flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <h4 class="text-xl font-black text-slate-400 uppercase tracking-widest">Coda Vuota</h4>
            <p class="text-slate-500 mt-2">Nessun ordine in attesa di approvazione corrisponde ai criteri impostati.</p>
            <a href="{{ route('orders.index') }}" class="mt-8 text-xs font-black text-indigo-600 uppercase tracking-widest underline decoration-2 underline-offset-8">Vai all'Archivio Vendite</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($orders as $ordine)
                @php
                    $days = (int) floor(\Carbon\Carbon::parse($ordine->Data)->diffInSeconds(now()) / 86400);
                    $priority = 'normal';
                    $priorityLabel = 'Normale';
                    
                    if ($days >= 3) {
                        $priority = 'high';
                        $priorityLabel = 'Priorità: Alta';
                    } elseif ($days == 2) {
                        $priority = 'medium';
                        $priorityLabel = 'Priorità: Media';
                    }
                @endphp
                <div class="approval-card group {{ $priority === 'high' ? 'approval-card-urgent' : '' }}">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ordine ID</span>
                                <span class="text-lg font-black text-slate-900 dark:text-white">#{{ $ordine->IDOrdineVendita }}</span>
                            </div>
                            
                            <span class="priority-badge priority-{{ $priority }}">
                                {{ $priorityLabel }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="client-avatar-placeholder">
                                {{ substr($ordine->cliente->Nome ?? 'C', 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-base font-black text-slate-900 dark:text-white leading-tight break-words line-clamp-2">
                                    {{ $ordine->cliente->Nome ?? 'Cliente Sconosciuto' }}
                                </h4>
                                <p class="text-[9px] font-bold uppercase tracking-widest mt-1.5" :class="'{{ $priority }}' === 'high' ? 'text-rose-500' : 'text-slate-400'">
                                    @if($days == 0)
                                        Ricevuto oggi
                                    @elseif($days == 1)
                                        Ricevuto ieri
                                    @else
                                        In coda da {{ $days }} giorni
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="order-detail-mini">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Riepilogo Prodotti</p>
                            <div class="space-y-3">
                                @foreach($ordine->dettagliVendita->take(3) as $dettaglio)
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-slate-600 dark:text-slate-400 font-medium line-clamp-1 flex-1">
                                            {{ $dettaglio->prodotto->Descrizione ?? 'Articolo non disponibile' }}
                                        </span>
                                        <span class="font-black text-slate-900 dark:text-slate-200 ml-4">
                                            x{{ $dettaglio->QuantitaRichiesta }}
                                        </span>
                                    </div>
                                @endforeach
                                @if(count($ordine->dettagliVendita) > 3)
                                    <p class="text-[10px] text-indigo-500 font-black uppercase tracking-widest mt-2">
                                        +{{ count($ordine->dettagliVendita) - 3 }} altri articoli
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800/50">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Totale da Pagare</p>
                                <p class="text-2xl font-black text-slate-900 dark:text-white">€ {{ number_format($ordine->totale_ordine, 2, ',', '.') }}</p>
                            </div>
                            
                            <div class="flex gap-3">
                                <form action="{{ route('orders.reject', $ordine->IDOrdineVendita) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-reject" title="Rifiuta Ordine">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>

                                <form action="{{ route('orders.approve', $ordine->IDOrdineVendita) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-approve flex items-center justify-center text-white transition-all shadow-xl" title="Approva Ordine">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <a href="{{ route('orders.show', $ordine->IDOrdineVendita) }}" class="block text-center text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">
                            Visualizza Dettagli Completi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
