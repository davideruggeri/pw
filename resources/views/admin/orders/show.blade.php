@extends('layouts.dashboard')

@section('title', "Dettaglio Ordine #$ordine->IDOrdineVendita")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sales-orders.css') }}">
@endpush

@section('content')
<div class="logistics-container animate-fade-in w-full max-w-[1500px] mx-auto py-10 px-8">
    
    <!-- Header: Più arioso e bilanciato -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-10 mb-16 px-2">
        <div class="flex items-center gap-6">
            <a href="{{ route('orders.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 transition-all group shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-4 mb-1">
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Dettaglio Ordine #{{ $ordine->IDOrdineVendita }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-[9px] font-black uppercase tracking-widest">Documentazione Vendita</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Eseguito il {{ \Carbon\Carbon::parse($ordine->Data)->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="flex flex-col items-end">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Avanzamento Ordine</span>
                <span class="status-badge status-{{ strtolower($ordine->Stato) }} px-6 py-2 text-[10px]">
                    {{ $ordine->Stato }}
                </span>
            </div>
            @if($ordine->Stato !== 'Spedito' && $ordine->Stato !== 'Annullato')
                <form action="{{ route('orders.ship', $ordine->IDOrdineVendita) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 hover:-translate-y-1 transition-all active:translate-y-0 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Conferma Spedizione
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Grid: 12 Columns (3 Sidebar, 9 Main) -->
    <div class="grid grid-cols-12 gap-12 items-start">
        
        <!-- Sidebar Info (col-span-3) -->
        <div class="col-span-12 lg:col-span-3 space-y-10 sticky top-10">
            
            <!-- Card Cliente -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex flex-col items-center text-center mb-10">
                    <div class="w-20 h-20 rounded-3xl bg-slate-900 dark:bg-indigo-600 flex items-center justify-center text-white text-3xl font-black shadow-2xl mb-4">
                        {{ substr($ordine->cliente->Nome ?? 'C', 0, 1) }}
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white leading-tight">{{ $ordine->cliente->Nome ?? 'N/D' }}</h3>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-2">ID: {{ $ordine->CodiceCliente_FK }}</p>
                </div>

                <div class="space-y-10 pt-10 border-t border-slate-100 dark:border-slate-800/50">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-1 h-4 bg-indigo-500 rounded-full"></div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Spedizione</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 font-bold leading-relaxed">{{ $ordine->cliente->IndirizzoSpedizione ?? 'N/D' }}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-1 h-4 bg-slate-400 rounded-full"></div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Dettaglio Fiscale</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 font-bold">P.IVA: {{ $ordine->cliente->PartitaIVA ?? 'N/D' }}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-1 h-4 bg-slate-400 rounded-full"></div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pagamento</span>
                        </div>
                        <p class="text-xs text-slate-900 dark:text-white font-black uppercase tracking-widest">Bonifico Bancario</p>
                        <p class="text-[10px] text-slate-400 font-bold mt-1">Netto 30 Giorni</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Area (col-span-9) -->
        <div class="col-span-12 lg:col-span-9 space-y-12">
            
            <!-- Timeline Tracker -->
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-12 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between relative max-w-3xl mx-auto py-4">
                    <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-100 dark:bg-slate-800 -translate-y-1/2"></div>
                    
                    <div class="flex flex-col items-center gap-5 relative z-10 bg-white dark:bg-slate-900 px-8">
                        <div class="w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-2xl shadow-emerald-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Creato</span>
                    </div>

                    <div class="flex flex-col items-center gap-5 relative z-10 bg-white dark:bg-slate-900 px-8">
                        <div class="w-14 h-14 rounded-full {{ in_array($ordine->Stato, ['Approvato', 'Spedito']) ? 'bg-emerald-500 text-white shadow-2xl shadow-emerald-500/30' : 'bg-slate-50 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }} flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-[11px] font-black {{ in_array($ordine->Stato, ['Approvato', 'Spedito']) ? 'text-slate-900 dark:text-white' : 'text-slate-400' }} uppercase tracking-widest">Approvato</span>
                    </div>

                    <div class="flex flex-col items-center gap-5 relative z-10 bg-white dark:bg-slate-900 px-8">
                        <div class="w-14 h-14 rounded-full {{ $ordine->Stato === 'Spedito' ? 'bg-indigo-600 text-white shadow-2xl shadow-indigo-600/30' : 'bg-slate-50 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }} flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <span class="text-[11px] font-black {{ $ordine->Stato === 'Spedito' ? 'text-slate-900 dark:text-white' : 'text-slate-400' }} uppercase tracking-widest">Spedito</span>
                    </div>
                </div>
            </div>

            <!-- Prodotti Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-12 py-10 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center bg-slate-50/20 dark:bg-slate-800/10">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Dettaglio Articoli</h3>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ count($ordine->dettagliVendita) }} Posizioni</span>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/20">
                            <th class="px-12 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Articolo</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Qtà</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Unitario</th>
                            <th class="px-12 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Totale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($ordine->dettagliVendita as $dettaglio)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-12 py-10">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-2xl border border-slate-200 dark:border-slate-700/50 shadow-sm group-hover:scale-110 transition-transform">📦</div>
                                    <div>
                                        <p class="text-base font-black text-slate-900 dark:text-white leading-tight mb-2">{{ $dettaglio->prodotto->Descrizione }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">SKU: {{ $dettaglio->CodiceUnivoco_FK }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-10 text-center">
                                <span class="px-5 py-2 bg-slate-100 dark:bg-slate-800 rounded-2xl text-xs font-black text-slate-700 dark:text-slate-300 tabular-nums">
                                    {{ (int)$dettaglio->QuantitaRichiesta }} pz
                                </span>
                            </td>
                            <td class="px-6 py-10 text-right font-bold text-slate-400 tabular-nums text-xs">
                                € {{ number_format($dettaglio->PrezzoApplicato, 2, ',', '.') }}
                            </td>
                            <td class="px-12 py-10 text-right font-black text-slate-900 dark:text-white tabular-nums text-base">
                                € {{ number_format($dettaglio->QuantitaRichiesta * $dettaglio->PrezzoApplicato, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Footer Riepilogo -->
                <div class="bg-slate-50/50 dark:bg-slate-800/20 px-10 py-10 border-t border-slate-100 dark:border-slate-800/50">
                    <div class="flex flex-col items-end gap-3">
                        <div class="flex justify-between w-full md:w-80 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                            <span>Subtotale</span>
                            <span class="text-slate-900 dark:text-white font-black">€ {{ number_format($ordine->totale_ordine * 0.78, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between w-full md:w-72 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                            <span>Imposta IVA (22%)</span>
                            <span class="text-slate-900 dark:text-white font-black">€ {{ number_format($ordine->totale_ordine * 0.22, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between w-full md:w-72 text-base font-black text-slate-900 dark:text-white uppercase mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <span class="tracking-tighter">Totale Ordine</span>
                            <span class="text-indigo-600 tracking-tight text-xl">€ {{ number_format($ordine->totale_ordine, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
