@extends('layouts.dashboard')

@section('title', 'Commerciale - Approvazione Ordini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
    <style>
        .approval-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .approval-card:hover {
            transform: scale(1.02);
            box-shadow: 0 40px 80px -15px rgba(0,0,0,0.1);
        }
        .btn-approve {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
        }
        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.6);
        }
    </style>
@endpush

@section('content')
<div class="logistics-container animate-fade-in">

    <div class="mb-12">
        <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Attesa Approvazione</h3>
        <p class="text-slate-500 text-sm">Revisione e validazione degli ordini in entrata.</p>
    </div>

    @if($orders->isEmpty())
        <div class="logistics-card p-20 text-center flex flex-col items-center border-0 shadow-sm">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <h4 class="text-xl font-black text-slate-400 uppercase tracking-widest">Tutto in Ordine</h4>
            <p class="text-slate-500 mt-2">Non ci sono nuovi ordini da approvare al momento.</p>
            <a href="{{ route('orders.index') }}" class="mt-8 text-xs font-black text-indigo-600 uppercase tracking-widest underline decoration-2 underline-offset-8">Vai all'Archivio</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($orders as $ordine)
                <div class="logistics-card p-8 border-0 shadow-lg approval-card flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ordine #{{ $ordine->IDOrdineVendita }}</span>
                            <span class="px-3 py-1 bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 rounded-full text-[8px] font-black uppercase tracking-widest">Pendente</span>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-lg font-black shadow-lg shadow-indigo-600/20 group-hover:rotate-6 transition-all">
                                {{ substr($ordine->cliente->Nome, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 dark:text-white leading-tight">{{ $ordine->cliente->Nome }}</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ count($ordine->dettagliVendita) }} Articoli</p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-8">
                            @foreach($ordine->dettagliVendita->take(3) as $dettaglio)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500 font-medium line-clamp-1 flex-1">{{ $dettaglio->prodotto->Descrizione }}</span>
                                    <span class="font-black text-slate-900 dark:text-slate-300 ml-4">x{{ $dettaglio->QuantitaRichiesta }}</span>
                                </div>
                            @endforeach
                            @if(count($ordine->dettagliVendita) > 3)
                                <p class="text-[10px] text-indigo-500 font-black uppercase tracking-widest">+{{ count($ordine->dettagliVendita) - 3 }} altri articoli</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Totale Ordine</p>
                            <p class="text-xl font-black text-slate-900 dark:text-white">€ {{ number_format($ordine->dettagliVendita->sum(fn($d) => $d->QuantitaRichiesta * $d->PrezzoApplicato), 2, ',', '.') }}</p>
                        </div>
                        
                        <form action="{{ route('orders.approve', $ordine->IDOrdineVendita) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-approve w-12 h-12 rounded-2xl flex items-center justify-center text-white transition-all hover:scale-110 active:scale-95 shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
