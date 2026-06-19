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
            @if($ordine->Stato === 'In Attesa' && Auth::user()->isSales())
                <div class="flex items-center gap-4">
                    <form action="{{ route('orders.approve', $ordine->IDOrdineVendita) }}" method="POST" onsubmit="return confirm('Confermare l\'approvazione dell\'ordine #{{ $ordine->IDOrdineVendita }}?')">
                        @csrf
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl shadow-emerald-600/20 hover:bg-emerald-500 hover:-translate-y-1 transition-all active:translate-y-0 flex items-center gap-3 cursor-pointer border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            Approva Ordine
                        </button>
                    </form>
                    <form action="{{ route('orders.reject', $ordine->IDOrdineVendita) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler rifiutare l\'ordine #{{ $ordine->IDOrdineVendita }}?')">
                        @csrf
                        <button type="submit" class="bg-rose-600 text-white px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl shadow-rose-600/20 hover:bg-rose-500 hover:-translate-y-1 transition-all active:translate-y-0 flex items-center gap-3 cursor-pointer border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            Rifiuta Ordine
                        </button>
                    </form>
                </div>
            @elseif($ordine->Stato === 'Approvato' && Auth::user()->isLogistics())
                <form action="{{ route('orders.ship', $ordine->IDOrdineVendita) }}" method="POST" onsubmit="return confirm('Confermare la spedizione dell\'ordine #{{ $ordine->IDOrdineVendita }}?')">
                    @csrf
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 hover:-translate-y-1 transition-all active:translate-y-0 flex items-center gap-3 cursor-pointer border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Conferma Spedizione
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Grid: Custom Grid System to ensure proper layout and avoid conflicts -->
    <div class="order-detail-layout-grid">
        
        <!-- Sidebar Info -->
        <div class="sticky top-10 flex flex-col gap-8">
            
            <!-- Card Cliente -->
            <div class="order-detail-glass-card">
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-20 h-20 rounded-3xl bg-indigo-600 flex items-center justify-center text-white text-3xl font-black shadow-2xl mb-4">
                        {{ substr($ordine->cliente->Nome ?? 'C', 0, 1) }}
                    </div>
                    <h3 class="text-xl font-black text-premium-primary leading-tight">{{ $ordine->cliente->Nome ?? 'N/D' }}</h3>
                    <p class="text-[10px] text-premium-muted font-black uppercase tracking-widest mt-2">ID: {{ $ordine->CodiceCliente_FK }}</p>
                </div>

                <div class="sidebar-field-divider"></div>

                <div class="space-y-8">
                    <div class="sidebar-field-group">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-4 bg-indigo-500 rounded-full"></div>
                            <span class="text-[10px] font-black text-premium-muted uppercase tracking-widest">Spedizione</span>
                        </div>
                        <p class="text-xs text-premium-secondary font-bold leading-relaxed">{{ $ordine->cliente->IndirizzoSpedizione ?? 'N/D' }}</p>
                    </div>

                    <div class="sidebar-field-divider"></div>

                    <div class="sidebar-field-group">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-4 bg-indigo-400 rounded-full"></div>
                            <span class="text-[10px] font-black text-premium-muted uppercase tracking-widest">Dettaglio Fiscale</span>
                        </div>
                        <p class="text-xs text-premium-secondary font-bold">P.IVA: {{ $ordine->cliente->PartitaIVA ?? 'N/D' }}</p>
                    </div>

                    <div class="sidebar-field-divider"></div>

                    <div class="sidebar-field-group">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-4 bg-indigo-400 rounded-full"></div>
                            <span class="text-[10px] font-black text-premium-muted uppercase tracking-widest">Pagamento</span>
                        </div>
                        <p class="text-xs text-premium-primary font-black uppercase tracking-widest">Bonifico Bancario</p>
                        <p class="text-[10px] text-premium-muted font-bold mt-1">Netto 30 Giorni</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Area -->
        <div class="flex flex-col gap-10">
            
            <!-- Timeline Tracker -->
            <div class="order-detail-glass-card">
                <div class="timeline-stepper">
                    <div class="timeline-step-node">
                        <div class="w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-2xl shadow-emerald-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-[11px] font-black text-premium-primary uppercase tracking-widest">Creato</span>
                    </div>

                    @if($ordine->Stato === 'Annullato')
                        <div class="timeline-step-node">
                            <div class="w-14 h-14 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-2xl shadow-rose-600/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            </div>
                            <span class="text-[11px] font-black text-rose-600 uppercase tracking-widest">Annullato</span>
                        </div>
                    @else
                        <div class="timeline-step-node">
                            <div class="w-14 h-14 rounded-full {{ in_array($ordine->Stato, ['Approvato', 'Spedito']) ? 'bg-emerald-500 text-white shadow-2xl shadow-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="text-[11px] font-black {{ in_array($ordine->Stato, ['Approvato', 'Spedito']) ? 'text-premium-primary' : 'text-premium-muted' }} uppercase tracking-widest">Approvato</span>
                        </div>

                        <div class="timeline-step-node">
                            <div class="w-14 h-14 rounded-full {{ $ordine->Stato === 'Spedito' ? 'bg-indigo-600 text-white shadow-2xl shadow-indigo-600/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                            <span class="text-[11px] font-black {{ $ordine->Stato === 'Spedito' ? 'text-premium-primary' : 'text-premium-muted' }} uppercase tracking-widest">Spedito</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Prodotti Card -->
            <div class="order-detail-glass-card !p-0">
                <div class="px-12 py-10 border-b border-slate-800 flex justify-between items-center bg-slate-800/10">
                    <h3 class="text-sm font-black text-premium-primary uppercase tracking-widest">Dettaglio Articoli</h3>
                    <span class="text-[11px] font-bold text-premium-muted uppercase tracking-widest">{{ count($ordine->dettagliVendita) }} Posizioni</span>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800/20">
                            <th class="px-12 py-6 text-[10px] font-black text-premium-muted uppercase tracking-widest">Articolo</th>
                            <th class="px-6 py-6 text-[10px] font-black text-premium-muted uppercase tracking-widest text-center">Qtà</th>
                            <th class="px-6 py-6 text-[10px] font-black text-premium-muted uppercase tracking-widest text-right">Unitario</th>
                            <th class="px-12 py-6 text-[10px] font-black text-premium-muted uppercase tracking-widest text-right">Totale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @foreach($ordine->dettagliVendita as $dettaglio)
                        <tr class="hover:bg-slate-800/30 transition-colors group">
                            <td class="px-12 py-8">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center text-2xl border border-slate-700/50 shadow-sm group-hover:scale-110 transition-transform">📦</div>
                                    <div>
                                        <p class="text-base font-black text-premium-primary leading-tight mb-2 break-words line-clamp-2">{{ $dettaglio->prodotto->Descrizione }}</p>
                                        <p class="text-[10px] text-premium-muted font-bold uppercase tracking-widest">SKU: {{ $dettaglio->CodiceUnivoco_FK }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-8 text-center">
                                <span class="px-5 py-2 bg-slate-800 rounded-2xl text-xs font-black text-premium-secondary tabular-nums">
                                    {{ (int)$dettaglio->QuantitaRichiesta }} pz
                                </span>
                            </td>
                            <td class="px-6 py-8 text-right font-bold text-premium-muted tabular-nums text-xs">
                                € {{ number_format($dettaglio->PrezzoApplicato, 2, ',', '.') }}
                            </td>
                            <td class="px-12 py-8 text-right font-black text-premium-primary tabular-nums text-base">
                                € {{ number_format($dettaglio->QuantitaRichiesta * $dettaglio->PrezzoApplicato, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Footer Riepilogo -->
                <div class="bg-slate-800/20 px-10 py-10 border-t border-slate-800">
                    <div class="flex flex-col items-end gap-3">
                        <div class="flex justify-between w-full md:w-80 text-[9px] font-bold text-premium-muted uppercase tracking-widest">
                            <span>Subtotale</span>
                            <span class="text-premium-primary font-black">€ {{ number_format($ordine->totale_ordine * 0.78, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between w-full md:w-72 text-[9px] font-bold text-premium-muted uppercase tracking-widest">
                            <span>Imposta IVA (22%)</span>
                            <span class="text-premium-primary font-black">€ {{ number_format($ordine->totale_ordine * 0.22, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between w-full md:w-72 text-base font-black text-premium-primary uppercase mt-4 pt-4 border-t border-slate-700">
                            <span class="tracking-tighter">Totale Ordine</span>
                            <span class="text-indigo-400 tracking-tight text-xl">€ {{ number_format($ordine->totale_ordine, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
