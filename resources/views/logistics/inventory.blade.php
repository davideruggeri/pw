@extends('layouts.dashboard')

@section('title', 'Magazzino - Inventario Completo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
@endpush

@section('content')
    <div class="logistics-container">

        <div class="mb-8 flex justify-between items-end">
            <div>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Inventario Reale</h3>
                <p class="text-slate-500 text-sm">Giacenze aggiornate in tempo reale in base a produzione e vendite.</p>
            </div>
        </div>

        <!-- Filtri e Ricerca -->
        <div class="logistics-filter-bar flex flex-wrap items-center justify-between gap-6">
            <form action="{{ route('logistics.inventory') }}" method="GET" class="flex-1 min-w-[300px]">
                <div class="logistics-search-wrapper">
                    <div class="logistics-search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cerca prodotto per nome, codice o descrizione..." class="logistics-input logistics-input-with-icon">
                </div>
            </form>

            <div class="per-page-container flex items-center bg-slate-100 dark:bg-slate-800/50 px-4 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700/50">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mr-2">Visualizza</span>
                <form action="{{ route('logistics.inventory') }}" method="GET">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
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

        <div class="logistics-card">
            <div class="overflow-x-auto">
                <table class="logistics-table">
                    <thead>
                        <tr class="logistics-table-header">
                            <th class="logistics-table-th">Codice</th>
                            <th class="logistics-table-th">Prodotto</th>
                            <th class="logistics-table-th">Categoria</th>
                            <th class="logistics-table-th text-right">Giacenza</th>
                            <th class="logistics-table-th text-right">Valore Unitario</th>
                            <th class="logistics-table-th text-right">Valore Totale</th>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-10 py-20 text-center text-slate-500">Nessun prodotto trovato.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginazione Premium -->
        @if($products->hasPages() || $products->total() > 0)
            <div class="pagination-centered-column">
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