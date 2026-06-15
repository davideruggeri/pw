@extends('layouts.dashboard')

@section('title', 'Amministrazione - Overview')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="animate-fade-in w-full max-w-[1500px] mx-auto py-6">

    <!-- KPI Cards Grid: Cinque metriche operative reali -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
        <!-- KPI Card: Fatturato Consolidato -->
        <div class="kpi-card shadow-sm group border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 hover:-translate-y-1 transition-all duration-300">
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <p class="kpi-label text-[10px] font-black tracking-widest text-slate-400 uppercase">Fatturato Consolidato</p>
                    <h3 class="kpi-value text-2xl font-black text-slate-900 dark:text-white mt-3">€ {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mt-4">Volume vendite approvate/spedite</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-50 dark:bg-indigo-950/20 rounded-full opacity-50 transition-transform group-hover:scale-110 duration-300"></div>
        </div>

        <!-- KPI Card: Ordini in Attesa di Approva -->
        <a href="{{ route('orders.pending') }}" class="kpi-card shadow-sm group border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 hover:-translate-y-1 transition-all duration-300 block text-decoration-none">
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <div class="flex justify-between items-start">
                        <p class="kpi-label text-[10px] font-black tracking-widest text-slate-400 uppercase">Ordini da Approvare</p>
                        @if($pendingOrdersCount > 0)
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </div>
                    <h3 class="kpi-value text-2xl font-black {{ $pendingOrdersCount > 0 ? 'text-rose-500 dark:text-rose-400' : 'text-slate-900 dark:text-white' }} mt-3">{{ $pendingOrdersCount }}</h3>
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mt-4 hover:text-rose-500 transition-colors">Vedi coda di approvazione →</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-50 dark:bg-rose-950/20 rounded-full opacity-50 transition-transform group-hover:scale-110 duration-300"></div>
        </a>

        <!-- KPI Card: Prodotti Sotto Scorta -->
        <a href="{{ route('logistics.inventory', ['tab' => 'replenishment']) }}" class="kpi-card shadow-sm group border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 hover:-translate-y-1 transition-all duration-300 block text-decoration-none">
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <div class="flex justify-between items-start">
                        <p class="kpi-label text-[10px] font-black tracking-widest text-slate-400 uppercase">Allarmi Sotto Scorta</p>
                        @if($lowStockCount > 0)
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                        @endif
                    </div>
                    <h3 class="kpi-value text-2xl font-black {{ $lowStockCount > 0 ? 'text-amber-500 dark:text-amber-400' : 'text-slate-900 dark:text-white' }} mt-3">{{ $lowStockCount }}</h3>
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mt-4 hover:text-amber-500 transition-colors">Gestisci rifornimenti →</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-50 dark:bg-amber-950/20 rounded-full opacity-50 transition-transform group-hover:scale-110 duration-300"></div>
        </a>

        <!-- KPI Card: Clienti Registrati -->
        <div class="kpi-card shadow-sm group border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 hover:-translate-y-1 transition-all duration-300">
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <p class="kpi-label text-[10px] font-black tracking-widest text-slate-400 uppercase">Clienti Registrati</p>
                    <h3 class="kpi-value text-2xl font-black text-slate-900 dark:text-white mt-3">{{ $totalCustomers }}</h3>
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mt-4">Totale account clienti registrati</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 dark:bg-emerald-950/20 rounded-full opacity-50 transition-transform group-hover:scale-110 duration-300"></div>
        </div>

        <!-- KPI Card: Dipendenti Attivi -->
        <a href="{{ route('employees.index') }}" class="kpi-card shadow-sm group border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 hover:-translate-y-1 transition-all duration-300 block text-decoration-none">
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <p class="kpi-label text-[10px] font-black tracking-widest text-slate-400 uppercase">Dipendenti Attivi</p>
                    <h3 class="kpi-value text-2xl font-black text-slate-900 dark:text-white mt-3">{{ $totalEmployees }}</h3>
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mt-4 hover:text-indigo-500 transition-colors">Gestione anagrafiche →</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-50 dark:bg-blue-950/20 rounded-full opacity-50 transition-transform group-hover:scale-110 duration-300"></div>
        </a>
    </div>

    <!-- Sezione Dettaglio: 2 Colonne -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Tabella Principale: Ordini e Prodotti più venduti (2/3 della larghezza) -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            
            <!-- Ultimi Ordini -->
            <div class="admin-table-container shadow-sm border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 rounded-3xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tighter">Ultimi Ordini Ricevuti</h4>
                    <a href="{{ route('orders.index') }}" class="text-[10px] font-black text-indigo-650 dark:text-indigo-400 uppercase tracking-widest hover:underline">Vedi Archivio Vendite →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="px-2">ID Ordine</th>
                                <th class="px-2">Cliente</th>
                                <th class="px-2">Data</th>
                                <th class="px-2">Stato</th>
                                <th class="px-2 text-right">Totale</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @forelse($recentOrders as $ordine)
                            <tr class="hover:bg-slate-50 transition duration-150">
                                <td class="px-2 font-medium">
                                    <a href="{{ route('orders.show', $ordine->IDOrdineVendita) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-black">
                                        #{{ $ordine->IDOrdineVendita }}
                                    </a>
                                </td>
                                <td class="px-2 text-slate-700 dark:text-slate-350 font-semibold">{{ $ordine->cliente->Nome ?? 'N/D' }}</td>
                                <td class="px-2 text-slate-500">{{ $ordine->Data ? \Carbon\Carbon::parse($ordine->Data)->format('d/m/Y') : '-' }}</td>
                                <td class="px-2">
                                    @php
                                        $statusClasses = [
                                            'In Attesa' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            'Approvato' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'Spedito' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                            'Annullato' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$ordine->Stato] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $ordine->Stato }}
                                    </span>
                                </td>
                                <td class="px-2 text-right font-black text-slate-900 dark:text-white">
                                    € {{ number_format($ordine->totale_ordine, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400 font-bold">Nessun ordine presente a sistema.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Prodotti Bestseller -->
            <div class="admin-table-container shadow-sm border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 rounded-3xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tighter">Articoli Bestseller</h4>
                    <a href="{{ route('inventory.index') }}" class="text-[10px] font-black text-indigo-650 dark:text-indigo-400 uppercase tracking-widest hover:underline">Vedi Inventario Magazzino →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="px-2">SKU</th>
                                <th class="px-2">Prodotto</th>
                                <th class="px-2 text-center">Unità Vendute</th>
                                <th class="px-2 text-right">Fatturato Generato</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @forelse($bestsellers as $product)
                            <tr class="hover:bg-slate-50 transition duration-150">
                                <td class="px-2 font-black text-slate-500">#{{ $product->CodiceUnivoco }}</td>
                                <td class="px-2 text-slate-800 dark:text-slate-200 font-bold break-words">{{ $product->NomeProdotto }}</td>
                                <td class="px-2 text-center">
                                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-350 rounded-lg text-xs font-black">
                                        {{ (int)$product->total_sold }} pz
                                    </span>
                                </td>
                                <td class="px-2 text-right font-black text-slate-900 dark:text-white">
                                    € {{ number_format($product->revenue, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 font-bold">Nessuna vendita registrata.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Destra: Top Clienti (1/3 della larghezza) -->
        <div class="flex flex-col gap-8">
            
            <!-- Classifica Clienti Top -->
            <div class="admin-table-container shadow-sm border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-900/40 rounded-3xl p-8">
                <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tighter mb-6">Top Clienti per Volume</h4>
                <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($customerStats as $customer)
                    <div class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-slate-850 dark:text-slate-200 text-sm">{{ $customer->Nome }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">ID: {{ $customer->CodiceCliente }}</p>
                        </div>
                        <p class="font-black text-slate-900 dark:text-white text-sm">€ {{ number_format($customer->revenue, 0, ',', '.') }}</p>
                    </div>
                    @empty
                    <p class="py-4 text-center text-slate-400 font-bold">Nessun cliente registrato.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
