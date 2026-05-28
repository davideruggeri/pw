@extends('layouts.dashboard')

@section('title', 'Dashboard Commerciale')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
@endpush

@section('content')
<div class="logistics-container animate-fade-in">

    <!-- KPI Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div class="logistics-card p-8 group relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Fatturato Mensile</p>
                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                    € {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-2 mt-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest">Target Raggiunto</p>
                </div>
            </div>
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-50 dark:bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-all"></div>
        </div>

        <div class="logistics-card p-8 group relative overflow-hidden border-emerald-500/20">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Margine Operativo</p>
                <h3 class="text-4xl font-black text-emerald-500 tracking-tighter">
                    € {{ number_format($monthlyMargin, 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-500 font-bold mt-4 uppercase tracking-[0.2em]">
                    <span class="text-emerald-500">{{ number_format(($monthlyMargin / ($monthlyRevenue ?: 1)) * 100, 1) }}%</span> Redditività Netta
                </p>
            </div>
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-50 dark:bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Tabella Ultimi Ordini -->
        <div class="lg:col-span-2 logistics-card shadow-sm border-0">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
                <div>
                    <h4 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter">Attività Recenti</h4>
                    <p class="text-xs text-slate-500 mt-1">Monitoraggio flussi di vendita in tempo reale</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn-quick-qty !py-2 !px-4 !text-[10px] uppercase">Vedi Archivio</a>
            </div>
            <div class="overflow-x-auto">
                <table class="logistics-table">
                    <thead>
                        <tr class="logistics-table-header">
                            <th class="logistics-table-th">Riferimento</th>
                            <th class="logistics-table-th">Cliente</th>
                            <th class="logistics-table-th">Stato</th>
                            <th class="logistics-table-th text-right">Valore Totale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($recentOrders as $ordine)
                        <tr class="logistics-table-tr group">
                            <td class="px-8 py-5">
                                <p class="text-sm font-black text-slate-900 dark:text-white">#{{ $ordine->IDOrdineVendita }}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ date('d/M/Y', strtotime($ordine->Data)) }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-black text-slate-500 uppercase">
                                        {{ substr($ordine->cliente->Nome, 0, 1) }}
                                    </div>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ordine->cliente->Nome }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $ordine->Stato == 'Completato' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ $ordine->Stato }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <p class="text-sm font-black text-slate-900 dark:text-white">€ {{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2, ',', '.') }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Classifica Prodotti & Portfolio -->
        <div class="space-y-8">
            <div class="logistics-card p-8 border-0 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Bestsellers</h4>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                </div>
                <div class="space-y-6">
                    @foreach($topProducts as $index => $product)
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500 text-[10px] font-black flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">{{ $index + 1 }}</span>
                            <div>
                                <p class="text-sm font-black text-slate-800 dark:text-white leading-tight group-hover:text-indigo-600 transition-colors">{{ $product->NomeProdotto }}</p>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ number_format($product->total_sold, 0, ',', '.') }} unità</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-emerald-500">+{{ number_format($product->profit / 1000, 1) }}k</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase">Profit</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800/50">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Top Clienti</p>
                        <span class="text-[10px] font-black text-indigo-500 uppercase">{{ $clienti->count() }} Totali</span>
                    </div>
                    <div class="flex -space-x-3">
                        @foreach($clienti->take(6) as $cliente)
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 border-4 border-white dark:border-slate-900 flex items-center justify-center text-xs font-black text-slate-600 dark:text-slate-300 shadow-sm hover:translate-y-[-4px] transition-transform cursor-pointer" title="{{ $cliente->Nome }}">
                                {{ substr($cliente->Nome, 0, 1) }}
                            </div>
                        @endforeach
                        @if($clienti->count() > 6)
                            <div class="w-10 h-10 rounded-2xl bg-slate-900 border-4 border-white dark:border-slate-900 flex items-center justify-center text-[10px] font-black text-white shadow-lg">
                                +{{ $clienti->count() - 6 }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
