@extends('layouts.dashboard')

@section('title', 'Magazzino - Inventario Completo')

@section('content')
<div class="max-w-6xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-8 flex justify-between items-end">
        <div>

            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Inventario Reale</h3>
            <p class="text-slate-500 text-sm">Giacenze aggiornate in tempo reale in base a produzione e vendite.</p>
        </div>
        <a href="{{ route('logistics.update') }}" class="btn-premium py-3 px-6 text-[10px]">
            Aggiorna Scorte
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-100 dark:border-slate-800/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-black/20">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Codice</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Prodotto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Categoria</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Giacenza</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Valore Unitario</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Valore Totale</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($products as $product)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                        <td class="px-6 py-4 font-black text-xs text-indigo-500">#{{ $product->CodiceUnivoco }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-black text-slate-800 dark:text-white leading-none">{{ $product->NomeProdotto }}</p>
                            <p class="text-[10px] text-slate-400 mt-1">{{ $product->Descrizione }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $product->categoria->NomeCategoria ?? 'N/D' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-block px-3 py-1 rounded-lg text-xs font-black {{ $product->Giacenza < 500 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
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
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-slate-800/50">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
