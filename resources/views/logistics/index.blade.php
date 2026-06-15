@extends('layouts.dashboard')

@section('title', 'Logistica - Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
@endpush

@section('content')
<div class="logistics-container">
    
    <!-- KPI Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="logistics-card p-8 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Valore Totale Magazzino</p>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                € {{ number_format($totalStockValue, 2, ',', '.') }}
            </h3>
        </div>
        
        <div class="bg-rose-600 p-8 rounded-3xl shadow-xl shadow-rose-500/20 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-2">Alert Scorte</p>
                <h3 class="text-4xl font-black text-white tracking-tighter">{{ $lowStockCount }}</h3>
                <p class="text-white/80 text-xs font-bold uppercase tracking-widest mt-1">Prodotti Sottoscorta</p>
            </div>
            <a href="{{ route('logistics.inventory', ['filter' => 'low_stock', 'tab' => 'replenishment']) }}" class="bg-white text-rose-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all active:scale-95">
                Vedi Criticità
            </a>
        </div>
    </div>

    <!-- Sezioni Principali -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Riassunto Attività -->
        <div class="lg:col-span-2 logistics-card shadow-sm">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
                <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Ultimi Arrivi</h4>
                <a href="{{ route('logistics.inventory') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">Inventario Completo</a>
            </div>
            <div class="overflow-x-auto">
                <table class="logistics-table">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($recentUpdates as $product)
                        <tr class="logistics-table-tr">
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-slate-800 dark:text-white">{{ $product->NomeProdotto }}</p>
                                <p class="text-[10px] text-slate-500 font-bold">Cod: {{ $product->CodiceUnivoco }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black text-slate-900 dark:text-white">
                                    {{ number_format($product->Giacenza, 0, ',', '.') }} {{ $product->UnitaMisura }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Scorciatoie -->
        <div class="space-y-6">
            <a href="{{ route('logistics.inventory', ['tab' => 'replenishment']) }}" class="block group">
                <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl border border-slate-800 group-hover:bg-slate-800 transition-all">
                    <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <h4 class="text-white font-black text-xl tracking-tight mb-2">Nuovo Rifornimento</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Apri il centro riacquisti per ordinare materiali.</p>
                </div>
            </a>

            <a href="{{ route('logistics.update') }}" class="block group">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 group-hover:border-indigo-500/30 transition-all">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-500 mb-6 group-hover:text-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </div>
                    <h4 class="text-slate-900 dark:text-white font-black text-xl tracking-tight mb-2">Carico/Scarico</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">Movimentazione manuale rapida delle merci.</p>
                </div>
            </a>
        </div>

    </div>

</div>
@endsection
