@extends('layouts.dashboard')

@section('title', 'Commerciale - Hub Vendite')

@section('content')
<div class="max-w-7xl mx-auto py-10 animate-fade-in px-4">

    <!-- KPI Header -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Fatturato Mensile</p>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                € {{ number_format($monthlyRevenue, 0, ',', '.') }}
            </h3>
            <p class="text-[10px] text-emerald-500 font-bold mt-2 uppercase tracking-widest">Target Raggiunto</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Margine Lordo</p>
            <h3 class="text-4xl font-black text-emerald-500 tracking-tighter">
                € {{ number_format($monthlyMargin, 0, ',', '.') }}
            </h3>
            <p class="text-[10px] text-slate-500 font-bold mt-2 uppercase tracking-widest">{{ number_format(($monthlyMargin / ($monthlyRevenue ?: 1)) * 100, 1) }}% Redditività</p>
        </div>

        <div class="bg-indigo-600 p-8 rounded-3xl shadow-xl shadow-indigo-500/20 flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-2">Azioni Rapide</p>
                <h3 class="text-xl font-black text-white tracking-tight">Pronto per una vendita?</h3>
            </div>
            <a href="{{ route('orders.create') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition-all text-center mt-4">
                Nuovo Ordine
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Tabella Ultimi Ordini -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
                <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Ordini Recenti</h4>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tutte le zone</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-black/20">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ordine</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Stato</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Valore</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($recentOrders as $ordine)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-slate-900 dark:text-white">#{{ $ordine->IDOrdineVendita }}</p>
                                <p class="text-[10px] text-slate-500">{{ date('d/m/Y', strtotime($ordine->Data)) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ordine->cliente->Nome }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest {{ $ordine->Stato == 'Completato' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                    {{ $ordine->Stato }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-black text-slate-900 dark:text-white">€ {{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2, ',', '.') }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Classifica Prodotti -->
        <div class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 p-6 shadow-sm">
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter mb-6">Bestsellers</h4>
            <div class="space-y-6">
                @foreach($topProducts as $index => $product)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black flex items-center justify-center">{{ $index + 1 }}</span>
                        <div>
                            <p class="text-sm font-black text-slate-800 dark:text-white leading-tight">{{ $product->NomeProdotto }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ number_format($product->total_sold, 0, ',', '.') }} Venduti</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-emerald-500">+€{{ number_format($product->profit / 1000, 1) }}k</p>
                </div>
                @endforeach
            </div>
            
            <div class="mt-10 pt-6 border-t border-slate-100 dark:border-slate-800/50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Portafoglio Clienti ({{ $clienti->count() }})</p>
                <div class="flex -space-x-2">
                    @foreach($clienti->take(8) as $cliente)
                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 border-2 border-white dark:border-slate-900 flex items-center justify-center text-[10px] font-black text-slate-600 dark:text-slate-400">
                            {{ substr($cliente->Nome, 0, 1) }}
                        </div>
                    @endforeach
                    @if($clienti->count() > 8)
                        <div class="w-8 h-8 rounded-full bg-indigo-600 border-2 border-white dark:border-slate-900 flex items-center justify-center text-[10px] font-black text-white">
                            +{{ $clienti->count() - 8 }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
