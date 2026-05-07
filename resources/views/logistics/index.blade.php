@extends('layouts.dashboard')

@section('title', 'Logistica e Magazzino - Reparto 4')

@section('content')
<div class="max-w-6xl mx-auto py-10 animate-fade-in">

    <!-- KPI Rapidi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Valore Magazzino</p>
                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                    € {{ number_format($totalWarehouseValue, 0, ',', '.') }}
                </h3>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Sotto Scorta</p>
                <h3 class="text-3xl font-black {{ $lowStockCount > 0 ? 'text-rose-500' : 'text-emerald-500' }} tracking-tighter">
                    {{ $lowStockCount }} <span class="text-sm font-bold text-slate-500 uppercase">Articoli</span>
                </h3>
            </div>
        </div>
        
        <!-- Form Rapido Aggiornamento Stock -->
        <div class="bg-slate-900 p-8 rounded-3xl shadow-xl border border-slate-800">
            <h4 class="text-white font-black text-lg mb-4 tracking-tight">Aggiorna Giacenza</h4>
            <form action="{{ route('logistics.update-stock') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <select name="CodiceUnivoco" required class="bg-white/5 border-slate-700 text-white rounded-xl text-xs font-bold focus:ring-indigo-500">
                        <option value="" disabled selected class="text-slate-900">Seleziona Prodotto...</option>
                        @foreach($prodotti as $p)
                            <option value="{{ $p->CodiceUnivoco }}" class="text-slate-900">{{ $p->Descrizione }} ({{ $p->QuantitaGiacenza }}kg)</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <input type="number" name="QuantitaGiacenza" placeholder="Nuova Q.tà" required class="flex-1 bg-white/5 border-slate-700 text-white rounded-xl text-xs font-bold placeholder-slate-500">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95">
                            Aggiorna
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventario -->
    <div class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">Inventario Prodotti</h4>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Stato Giacenze</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-black/20">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Codice</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Descrizione</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Giacenza</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Scorta Minima</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($prodotti as $p)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">#{{ $p->CodiceUnivoco }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $p->Descrizione }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ $p->categoria->NomeCategoria ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black {{ $p->QuantitaGiacenza <= $p->ScortaMinima ? 'text-rose-500' : 'text-slate-900 dark:text-white' }}">
                                {{ number_format($p->QuantitaGiacenza, 0, ',', '.') }} kg
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ number_format($p->ScortaMinima, 0, ',', '.') }} kg</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($p->QuantitaGiacenza <= $p->ScortaMinima)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-rose-100 text-rose-600 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    Sotto Scorta
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-emerald-100 text-emerald-600 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Ottimale
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($prodotti->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-slate-800/50">
            {{ $prodotti->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
