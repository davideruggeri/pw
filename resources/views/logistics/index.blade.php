@extends('layouts.dashboard')

@section('title', 'Logistica - Dashboard')

@section('content')
<div class="max-w-6xl mx-auto py-10 animate-fade-in">
    
    <!-- KPI Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Valore Totale Magazzino</p>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                € {{ number_format($totalStockValue, 2, ',', '.') }}
            </h3>
        </div>
        
        <div class="bg-amber-500 p-8 rounded-3xl shadow-xl shadow-amber-500/20 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-2">Alert Scorte</p>
                <h3 class="text-4xl font-black text-white tracking-tighter">{{ $lowStockCount }}</h3>
                <p class="text-white/80 text-xs font-bold uppercase tracking-widest mt-1">Articoli Sottoscorta</p>
            </div>
            <a href="{{ route('logistics.inventory') }}" class="bg-white text-amber-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all active:scale-95">
                Vedi Inventario
            </a>
        </div>
    </div>

    <!-- Azioni e Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
                <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Movimentazioni Recenti</h4>
                <a href="{{ route('logistics.inventory') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">Vedi Tutto</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($recentUpdates as $product)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-slate-800 dark:text-white">{{ $product->NomeProdotto }}</p>
                                <p class="text-[10px] text-slate-500 uppercase font-bold">{{ $product->categoria->NomeCategoria ?? 'Materiale' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black {{ $product->Giacenza < 500 ? 'text-rose-500' : 'text-emerald-500' }}">
                                    {{ number_format($product->Giacenza, 0, ',', '.') }} {{ $product->UnitaMisura }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-slate-900 p-8 rounded-3xl shadow-xl flex flex-col justify-between border border-slate-800">
            <div>
                <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                </div>
                <h4 class="text-white font-black text-xl tracking-tight mb-2">Movimenta Merci</h4>
                <p class="text-slate-400 text-sm mb-8 leading-relaxed">Registra l'arrivo di nuove materie prime o lo scarico per spedizione.</p>
            </div>
            <a href="{{ route('logistics.update') }}" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all text-center">
                Vai al Carico/Scarico
            </a>
        </div>

    </div>

</div>
@endsection
