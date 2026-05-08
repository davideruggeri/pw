@extends('layouts.dashboard')

@section('title', 'Magazzino - Movimentazione')

@section('content')
<div class="max-w-3xl mx-auto py-10 animate-fade-in">
    
    <div class="mb-10">

        <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Aggiorna Giacenze</h3>
        <p class="text-slate-500 text-sm">Inserisci i carichi da fornitore o gli scarichi manuali.</p>
    </div>

    @if(session('error'))
    <div class="bg-rose-100 border border-rose-200 text-rose-600 px-6 py-4 rounded-2xl mb-8 font-bold text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] shadow-xl border border-slate-100 dark:border-slate-800">
        <form action="{{ route('logistics.update-stock') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Prodotto</label>
                    <select name="IDProdotto_FK" required class="w-full bg-slate-50 dark:bg-slate-800/50 border-0 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">Seleziona Prodotto...</option>
                        @foreach($products as $product)
                        <option value="{{ $product->CodiceUnivoco }}">{{ $product->NomeProdotto }} (Giac: {{ $product->Giacenza }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipo Movimento</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl cursor-pointer hover:bg-emerald-50 transition-all border-2 border-transparent has-[:checked]:border-emerald-500 group">
                            <input type="radio" name="Tipo" value="carico" checked class="hidden">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-600 group-hover:text-emerald-600">Carico</span>
                        </label>
                        <label class="relative flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl cursor-pointer hover:bg-rose-50 transition-all border-2 border-transparent has-[:checked]:border-rose-500 group">
                            <input type="radio" name="Tipo" value="scarico" class="hidden">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-600 group-hover:text-rose-600">Scarico</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Quantità</label>
                <input type="number" name="Quantita" required min="1" step="0.01" placeholder="Inserisci valore..." class="w-full bg-slate-50 dark:bg-slate-800/50 border-0 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-indigo-500/20 active:scale-[0.98]">
                    Conferma Movimento
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
