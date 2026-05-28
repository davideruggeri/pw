@extends('layouts.dashboard')

@section('title', 'Logistica - Storico Rifornimenti')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
@endpush

@section('content')
    <div class="logistics-container">

        <div class="mb-10 flex justify-between items-end">
            <div>
                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Storico Rifornimenti</h3>
                <p class="text-slate-500 text-sm">Registro completo dei carichi di magazzino e dei costi sostenuti.</p>
            </div>
        </div>

        <!-- KPI Cards Section -->
        <div class="kpi-grid">
            <!-- Totale Rifornimenti -->
            <div class="kpi-card">
                <div class="kpi-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Operazioni Carico</p>
                    <p class="kpi-value">{{ $movements->total() }}</p>
                </div>
            </div>

            <!-- Quantità Totale Caricata -->
            <div class="kpi-card">
                <div class="kpi-icon-container kpi-icon-container-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Unità Rifornite</p>
                    <p class="kpi-value">{{ number_format($totalReplenishedQuantity, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Spesa Totale -->
            <div class="kpi-card">
                <div class="kpi-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Spesa Totale</p>
                    <p class="kpi-value">€ {{ number_format($totalReplenishmentCost, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Filtri e Ricerca -->
        <div class="logistics-filter-bar">
            <div class="filter-left-section">
                <!-- Ricerca -->
                <form action="{{ url()->current() }}" method="GET" class="flex-1 min-w-[280px]">
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
                            placeholder="Cerca per descrizione prodotto o codice..." class="logistics-input logistics-input-with-icon">
                    </div>
                </form>
            </div>

            <!-- Paginazione Quantità -->
            <div class="per-page-container flex items-center bg-slate-100 dark:bg-slate-800/50 px-4 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700/50">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mr-2">Righe</span>
                <form action="{{ url()->current() }}" method="GET">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    <select name="per_page" onchange="this.form.submit()"
                        class="bg-transparent border-0 text-[11px] font-black text-slate-900 dark:text-white focus:ring-0 cursor-pointer py-0 pr-8 pl-0">
                        @foreach([15, 30, 60, 120] as $val)
                            <option value="{{ $val }}" {{ ($perPage ?? 15) == $val ? 'selected' : '' }}
                                class="bg-white dark:bg-slate-900">{{ $val }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="logistics-card">
            <div class="overflow-x-auto">
                <table class="logistics-table">
                    <thead>
                        <tr class="logistics-table-header">
                            <th class="logistics-table-th">ID Movimento</th>
                            <th class="logistics-table-th">Codice Prodotto</th>
                            <th class="logistics-table-th">Prodotto</th>
                            <th class="logistics-table-th text-right">Quantità Rifornita</th>
                            <th class="logistics-table-th text-right">Costo Totale</th>
                            <th class="logistics-table-th">Data e Ora</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @forelse($movements as $m)
                            <tr class="logistics-table-tr">
                                <td class="px-6 py-4 font-black text-xs text-slate-500">#{{ $m->IDMovimento }}</td>
                                <td class="px-6 py-4 font-black text-xs text-indigo-500">#{{ $m->CodiceUnivoco_FK }}</td>
                                <td class="px-6 py-4">
                                    @if($m->prodotto)
                                        <p class="text-sm font-black text-slate-800 dark:text-white leading-none">
                                            {{ $m->prodotto->NomeProdotto }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">{{ Str::limit($m->prodotto->Descrizione, 50) }}</p>
                                    @else
                                        <span class="text-slate-400 italic">Prodotto eliminato</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-black bg-emerald-100 text-emerald-600">
                                        +{{ number_format($m->Quantita, 0, ',', '.') }} {{ $m->prodotto->UnitaMisura ?? '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-black text-slate-900 dark:text-white">
                                    € {{ number_format($m->CostoTotale, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 font-medium">
                                    {{ date('d/m/Y H:i', strtotime($m->DataMovimento)) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-10 py-20 text-center text-slate-500">Nessun rifornimento trovato.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginazione Premium -->
        @if($movements->hasPages() || $movements->total() > 0)
            <div class="pagination-centered-column mt-8">
                <p class="pagination-label">
                    Visualizzazione da {{ $movements->firstItem() }} a {{ $movements->lastItem() }} di {{ $movements->total() }}
                    movimenti
                </p>
                <div class="premium-pagination">
                    {{ $movements->appends(request()->input())->links() }}
                </div>
            </div>
        @endif

    </div>
@endsection
