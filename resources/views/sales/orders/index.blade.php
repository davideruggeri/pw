@extends('layouts.dashboard')

@section('title', 'Commerciale - Archivio Ordini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales-orders.css') }}">
@endpush

@section('content')
    <div class="logistics-container animate-fade-in">

        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Archivio Vendite</h3>
                <p class="text-slate-500 text-sm">Cronologia completa degli ordini e dei flussi commerciali.</p>
            </div>

            <!-- Mini Stats Bar -->
            <div class="flex gap-4">
                <div class="stats-card-premium flex flex-col">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Oggi</span>
                    <span class="text-xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['total_today'] }}</span>
                </div>
                <div class="stats-card-premium flex flex-col">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Settimana</span>
                    <span class="text-xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['total_week'] }}</span>
                </div>
                <div class="stats-card-premium stats-card-accent flex flex-col">
                    <span class="text-[8px] font-black stats-label uppercase tracking-widest">In Attesa</span>
                    <span class="text-xl font-black stats-label tabular-nums">{{ $stats['pending'] }}</span>
                </div>
            </div>
        </div>

        <!-- Filtri e Ricerca (Stessa logica Logistica) -->
        <div class="logistics-filter-bar flex flex-wrap items-center justify-between gap-6">
            <form action="{{ route('orders.index') }}" method="GET" class="flex-1 min-w-[300px]">
                <div class="logistics-search-wrapper">
                    <div class="logistics-search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cerca per ID ordine o nome cliente..."
                        class="logistics-input logistics-input-with-icon">
                </div>
            </form>

            <div class="flex items-center gap-4">
                <form action="{{ route('orders.index') }}" method="GET" class="flex items-center gap-4">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                    <select name="status" onchange="this.form.submit()"
                        class="logistics-input !py-2 !px-4 !text-xs !rounded-xl">
                        <option value="">Tutti gli Stati</option>
                        @foreach(['Inviato', 'Completato', 'Annullato'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}
                            </option>
                        @endforeach
                    </select>

                    <div
                        class="per-page-container flex items-center bg-slate-100 dark:bg-slate-800/50 px-4 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700/50">
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mr-2">Visualizza</span>
                        <select name="per_page" onchange="this.form.submit()"
                            class="bg-transparent border-0 text-[11px] font-black text-slate-900 dark:text-white focus:ring-0 cursor-pointer py-0 pr-8 pl-0">
                            @foreach([10, 25, 50, 100] as $val)
                                <option value="{{ $val }}" {{ ($perPage ?? 10) == $val ? 'selected' : '' }}
                                    class="bg-white dark:bg-slate-900">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabella Ordini -->
        <div class="logistics-card shadow-sm border-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="logistics-table">
                    <thead>
                        <tr class="logistics-table-header">
                            <th class="logistics-table-th">Ordine</th>
                            <th class="logistics-table-th">Data emissione</th>
                            <th class="logistics-table-th">Cliente</th>
                            <th class="logistics-table-th">Stato</th>
                            <th class="logistics-table-th text-right">Totale Lordo</th>
                            <th class="logistics-table-th text-center">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @forelse($orders as $ordine)
                            <tr class="logistics-table-tr group">
                                <td class="px-8 py-5">
                                    <span
                                        class="text-sm font-black text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors">#{{ $ordine->IDOrdineVendita }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400">
                                        {{ date('d/m/Y', strtotime($ordine->Data)) }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-black">
                                        {{ date('H:i', strtotime($ordine->Data)) }}</p>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-xs font-black text-indigo-600 dark:text-indigo-400">
                                            {{ substr($ordine->cliente->Nome, 0, 1) }}
                                        </div>
                                        <span
                                            class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ordine->cliente->Nome }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="status-badge status-{{ strtolower($ordine->Stato) }}">
                                        {{ $ordine->Stato }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <p class="text-sm font-black text-slate-900 dark:text-white tabular-nums">
                                        € {{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2, ',', '.') }}
                                    </p>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('orders.show', $ordine->IDOrdineVendita) }}"
                                            class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-800/50 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200 mb-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-slate-400 font-bold">Nessun ordine trovato con i criteri selezionati.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($orders->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif

    </div>
@endsection