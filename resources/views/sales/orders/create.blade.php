@extends('layouts.dashboard')

@section('title', 'Nuovo Ordine di Vendita')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <div class="glass-card p-8 bg-white border-0 shadow-sm">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Inserimento Ordine</h3>

        @if($errors->has('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-100 rounded-xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ $errors->first('error') }}
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            
            <!-- Selezione Cliente -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Seleziona Cliente</label>
                <select name="CodiceCliente_FK" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                    <option value="">-- Seleziona un cliente --</option>
                    @foreach($clienti as $cliente)
                        <option value="{{ $cliente->CodiceCliente }}">{{ $cliente->RagioneSociale }} ({{ $cliente->CodiceCliente }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Selezione Prodotti -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-sm font-semibold text-slate-700">Articoli in Ordine</label>
                    <button type="button" onclick="addProductRow()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition uppercase tracking-wider flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Aggiungi Riga
                    </button>
                </div>

                <div id="product-rows" class="space-y-3">
                    <div class="product-row grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-8">
                            <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 ml-2">Prodotto</label>
                            <select name="prodotti[0][CodiceUnivoco]" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                                <option value="">-- Scegli Prodotto --</option>
                                @foreach($prodotti as $prodotto)
                                    <option value="{{ $prodotto->CodiceUnivoco }}">
                                        {{ $prodotto->NomeProdotto }} (Stock: {{ $prodotto->QuantitaGiacenza }} - € {{ $prodotto->PrezzoUnitario }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 ml-2">Qtà</label>
                            <input type="number" name="prodotti[0][Quantita]" value="1" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                        </div>
                        <div class="col-span-1 pb-2">
                            <button type="button" class="text-slate-300 hover:text-red-500 transition opacity-0 cursor-default">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t flex items-center justify-between">
                <a href="{{ route('sales.dashboard') }}" class="text-slate-500 hover:text-slate-800 transition text-sm font-medium">Annulla</a>
                <button type="submit" class="btn-premium px-8 py-3">Conferma e Invia Ordine</button>
            </div>
        </form>
    </div>
</div>

<script>
    let rowCount = 1;
    const productsOptions = `@foreach($prodotti as $prodotto)
        <option value="{{ $prodotto->CodiceUnivoco }}">
            {{ $prodotto->NomeProdotto }} (Stock: {{ $prodotto->QuantitaGiacenza }})
        </option>
    @endforeach`;

    function addProductRow() {
        const container = document.getElementById('product-rows');
        const row = document.createElement('div');
        row.className = 'product-row grid grid-cols-12 gap-4 items-end animate-fade-in';
        row.innerHTML = `
            <div class="col-span-8">
                <select name="prodotti[${rowCount}][CodiceUnivoco]" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                    <option value="">-- Scegli Prodotto --</option>
                    ${productsOptions}
                </select>
            </div>
            <div class="col-span-3">
                <input type="number" name="prodotti[${rowCount}][Quantita]" value="1" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
            </div>
            <div class="col-span-1 pb-2">
                <button type="button" onclick="this.closest('.product-row').remove()" class="text-slate-300 hover:text-red-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(row);
        rowCount++;
    }
</script>
@endsection
