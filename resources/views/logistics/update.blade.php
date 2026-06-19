@extends('layouts.dashboard')

@section('title', 'Magazzino - Movimentazione Rapida')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/logistics.css') }}">
@endpush

@section('content')
    @php 
            $allProducts = $products->map(function ($p) {
            return [
                'id' => $p->CodiceUnivoco,
                'name' => $p->NomeProdotto,
                'giacenza' => (float) $p->Giacenza,
                'minima' => (float) $p->ScortaMinima,
                'um' => $p->UnitaMisura,
                'categoria_id' => $p->IDCategoria_FK
            ];
        })->values();
    @endphp

    <div class="logistics-container" x-data='{ 
        searchTerm: "",
        selectedId: "{{ request("id") ?? "" }}",
        allProducts: @json($allProducts),
        movementType: "carico",
        quantity: 0,
        dropdownOpen: false,

        get selectedProduct() {
            return this.allProducts.find(p => p.id == this.selectedId) || null;
        },
        get filteredProducts() {
            const term = this.searchTerm.toLowerCase();
            return this.allProducts.filter(p => 
                !term || p.name.toLowerCase().includes(term) || p.id.toString().includes(term)
            );
        },
        get futureStock() {
            if (!this.selectedProduct) return 0;
            const q = parseFloat(this.quantity) || 0;
            return this.movementType === "carico" ? this.selectedProduct.giacenza + q : this.selectedProduct.giacenza - q;
        },
        addQty(val) {
            this.quantity = (parseFloat(this.quantity) || 0) + val;
        },
        selectByCode() {
            const p = this.allProducts.find(p => p.id.toString() === this.searchTerm);
            if (p) {
                this.selectedId = p.id;
                this.searchTerm = "";
                this.dropdownOpen = false;
            }
        }
    }'>
        <!-- Header Sezione -->
        <div class="mb-10">
            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Aggiornamento Scorte</h3>
            <p class="text-slate-500 text-sm">Inserisci il codice o seleziona un prodotto dalla tendina.</p>
        </div>

        <!-- KPI Cards Section -->
        <div class="kpi-grid">
            <!-- Totale Articoli -->
            <div class="kpi-card">
                <div class="kpi-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Articoli in Anagrafica</p>
                    <p class="kpi-value">{{ $totalProductsCount }}</p>
                </div>
            </div>

            <!-- Movimenti Oggi -->
            <div class="kpi-card">
                <div class="kpi-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Movimenti Oggi</p>
                    <p class="kpi-value">{{ $todayMovementsCount }}</p>
                </div>
            </div>

            <!-- Prodotti Sotto Scorta -->
            <div class="kpi-card {{ $lowStockCount > 0 ? 'kpi-card-danger' : '' }}">
                <div class="kpi-icon-container {{ $lowStockCount > 0 ? 'kpi-icon-container-danger' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="kpi-details">
                    <p class="kpi-title">Allarmi Sotto Scorta</p>
                    <p class="kpi-value">{{ $lowStockCount }}</p>
                </div>
            </div>
        </div>

        <!-- SELEZIONE PRODOTTO (TOP BAR) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
            <!-- Input Codice con Lente -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Cerca per Codice</label>
                <div class="logistics-search-wrapper">
                    <div class="logistics-search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="searchTerm" @input="selectByCode()"
                        placeholder="Inserisci codice univoco..."
                        class="logistics-input logistics-input-with-icon !bg-white dark:!bg-slate-900 !rounded-xl">
                </div>
            </div>

            <!-- Tendina Prodotti -->
            <div class="space-y-2 relative">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Seleziona dalla
                    Lista</label>
                <button @click="dropdownOpen = !dropdownOpen"
                    class="w-full flex items-center justify-between px-6 py-4 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-800 rounded-xl text-sm font-black transition-all hover:border-indigo-500 overflow-hidden">
                    <span class="truncate pr-4" x-text="selectedProduct ? selectedProduct.name : 'Scegli un prodotto...'"
                        :class="selectedProduct ? 'text-indigo-600' : 'text-slate-400'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 shrink-0 transition-transform"
                        :class="dropdownOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                    class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-2xl z-50 overflow-hidden animate-fade-in">
                    <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                        <template x-for="p in filteredProducts" :key="p.id">
                            <button @click="selectedId = p.id; dropdownOpen = false"
                                class="w-full flex items-center justify-between p-4 hover:bg-indigo-600 group transition-all text-left border-b border-slate-100 dark:border-slate-800/50 last:border-0">
                                <div class="min-w-0 pr-4 flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 dark:bg-slate-800 group-hover:bg-white/20 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 dark:text-indigo-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black group-hover:text-white truncate" x-text="p.name"></p>
                                        <p class="text-[9px] font-bold text-slate-400 group-hover:text-indigo-100 uppercase tracking-tighter"
                                            x-text="'Codice: ' + p.id"></p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs font-black group-hover:text-white" x-text="p.giacenza"></p>
                                    <p class="text-[8px] font-bold text-slate-400 group-hover:text-indigo-100 uppercase" x-text="p.um"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- AREA OPERATIVA -->
        <template x-if="selectedProduct">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-fade-in">
                <!-- Form Card -->
                <div class="lg:col-span-7">
                    <div class="logistics-card p-10">
                        <form action="{{ route('logistics.update-stock') }}" method="POST" class="space-y-10" @submit.prevent="if(confirm('Confermare il ' + (movementType === 'carico' ? 'carico' : 'scarico') + ' di ' + quantity + ' ' + selectedProduct.um + ' per il prodotto ' + selectedProduct.name + '?')) $el.submit()">
                            @csrf
                            <input type="hidden" name="IDProdotto_FK" :value="selectedId">
                            <input type="hidden" name="Tipo" :value="movementType">

                            <!-- Direzione -->
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipo
                                    Operazione</label>
                                <div class="grid grid-cols-2 p-1.5 bg-slate-50 dark:bg-black/40 rounded-xl gap-1.5">
                                    <button type="button" @click="movementType = 'carico'"
                                        class="py-5 rounded-lg text-xs font-black uppercase transition-all"
                                        :class="movementType === 'carico' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-slate-400'">
                                        Carico (+)
                                    </button>
                                    <button type="button" @click="movementType = 'scarico'"
                                        class="py-5 rounded-lg text-xs font-black uppercase transition-all"
                                        :class="movementType === 'scarico' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'text-slate-400'">
                                        Scarico (-)
                                    </button>
                                </div>
                            </div>

                            <!-- Quantità -->
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Quantità
                                    da Movimentare</label>
                                <input type="number" name="Quantita" x-model="quantity" step="0.01" required
                                    class="logistics-input w-full !text-4xl !py-6 text-center bg-slate-50 dark:bg-black/20">

                                <div class="grid grid-cols-2 gap-4">
                                    <template x-for="val in [10, 50, 100, 500]">
                                        <button type="button" @click="addQty(val)"
                                            class="btn-quick-qty !py-4 !text-sm !rounded-xl transition-all"
                                            :class="movementType === 'carico' ? 'btn-quick-qty-carico' : 'btn-quick-qty-scarico'"
                                            x-text="'+' + val"></button>
                                    </template>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-7 text-white rounded-2xl font-black text-base uppercase tracking-[0.25em] transition-all shadow-2xl active:scale-95 flex items-center justify-center gap-3 relative overflow-hidden group hover:scale-[1.02] border-0 cursor-pointer"
                                :class="movementType === 'carico' ? 'bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 shadow-emerald-500/40 hover:shadow-emerald-500/60' : 'bg-gradient-to-r from-rose-500 via-rose-600 to-red-600 shadow-rose-500/40 hover:shadow-rose-500/60'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 relative z-10 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                </svg>
                                <span class="relative z-10" x-text="movementType === 'carico' ? 'Conferma Carico (+)' : 'Conferma Scarico (-)'"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="lg:col-span-5 space-y-20">
                    <div class="logistics-card p-8 text-white border-0 relative overflow-hidden group preview-card-dynamic"
                        :class="movementType === 'carico' ? 'preview-card-carico' : 'preview-card-scarico'">
                        <!-- Background Decoration -->
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all duration-700"></div>
                        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all duration-700"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-8">
                                <div class="min-w-0 flex-1 pr-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Dettaglio Prodotto</p>
                                    <h4 class="text-xl md:text-2xl lg:text-3xl font-black tracking-tighter leading-tight break-words text-white" x-text="selectedProduct.name"></h4>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest" x-text="'ID: ' + selectedProduct.id"></p>
                                </div>
                                <div class="w-12 h-12 bg-white/5 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Stock Stats Grid -->
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="bg-white/5 backdrop-blur-sm p-4 rounded-2xl border border-white/5">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Attuale</p>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-black text-white" x-text="selectedProduct.giacenza"></span>
                                        <span class="text-[10px] font-bold opacity-60 uppercase text-slate-400" x-text="selectedProduct.um"></span>
                                    </div>
                                </div>
                                <div class="bg-white/5 backdrop-blur-sm p-4 rounded-2xl border border-white/5">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Proiezione</p>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-4xl font-black text-white" x-text="futureStock"></span>
                                        <span class="text-[10px] font-bold opacity-60 uppercase text-slate-400" x-text="selectedProduct.um"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Progress Section -->
                            <div class="space-y-3">
                                <div class="flex justify-between items-end">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Salute Stock</p>
                                    <p class="text-[10px] font-black uppercase" :class="futureStock < selectedProduct.minima ? 'text-rose-400' : 'text-emerald-400'" 
                                       x-text="futureStock < selectedProduct.minima ? 'Sotto Scorta Minima' : 'Livello Ottimale'"></p>
                                </div>
                                <div class="h-4 bg-white/10 rounded-full overflow-hidden p-1 border border-white/5">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out relative"
                                        :style="'width: ' + Math.min((futureStock / (selectedProduct.minima * 2)) * 100, 100) + '%'"
                                        :class="futureStock < selectedProduct.minima ? 'bg-gradient-to-r from-rose-500 to-rose-400 shadow-[0_0_15px_rgba(244,63,94,0.5)]' : 'bg-gradient-to-r from-emerald-500 to-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.5)]'">
                                    </div>
                                </div>
                                <div class="flex justify-between text-[8px] font-black text-slate-400 uppercase tracking-tighter">
                                    <span>0</span>
                                    <span x-text="'Minima: ' + selectedProduct.minima"></span>
                                    <span x-text="'Target: ' + (selectedProduct.minima * 2)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="logistics-card p-6 flex items-center gap-6 border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Verifica Fisica</h5>
                            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 leading-relaxed">Confronta sempre la giacenza fisica prima di validare il movimento a sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="!selectedProduct">
            <div
                class="py-32 text-center bg-white dark:bg-slate-900/40 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <!-- Background Glow -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-indigo-500/10 rounded-full blur-[100px]"></div>
                
                <div class="relative z-10">
                    <div
                        class="w-24 h-24 bg-gradient-to-tr from-indigo-500 to-violet-600 rounded-[2rem] flex items-center justify-center mx-auto mb-10 shadow-2xl transform -rotate-6 hover:rotate-0 transition-transform duration-500 group cursor-default">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white group-hover:scale-110 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Aggiornamento Scorte Rapido</h3>
                    <p class="text-slate-400 text-sm mt-4 font-medium max-w-sm mx-auto leading-relaxed">Seleziona un prodotto dalla lista o scansiona il codice per iniziare la movimentazione.</p>
                </div>
            </div>
        </template>
    </div>

    @push('styles')
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 5px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(99, 102, 241, 0.2);
                border-radius: 10px;
            }

            .animate-fade-in {
                animation: fadeIn 0.4s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush
@endsection