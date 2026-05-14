@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900 flex flex-col items-center justify-center px-4">
    <div class="max-w-4xl w-full text-center">
        <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">Role Switcher <span class="text-indigo-400">(Debug Mode)</span></h1>
        <p class="text-slate-400 mb-10">Seleziona un ruolo per testare istantaneamente le diverse dashboard e i permessi.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                /* 
                   Definiamo un array di ruoli con i relativi metadati per il debug switcher:
                   - id: l'identificativo usato nel backend per cambiare sessione
                   - name: il nome visualizzato sulla card
                   - color: la classe Tailwind per il colore di sfondo dell'icona
                   - desc: una breve descrizione dei permessi associati
                */
                $roles = [
                    ['id' => 'admin', 'name' => 'Amministratore', 'color' => 'bg-indigo-600', 'desc' => 'Accesso totale'],
                    ['id' => 'sales', 'name' => 'Venditore', 'color' => 'bg-emerald-600', 'desc' => 'Ordini e Clienti'],
                    ['id' => 'logistics', 'name' => 'Logistica', 'color' => 'bg-amber-600', 'desc' => 'Magazzino e Stock'],

                    ['id' => 'customer', 'name' => 'Cliente', 'color' => 'bg-slate-700', 'desc' => 'Area riservata'],
                ];
            @endphp

            @foreach($roles as $role)
            <!-- Form POST per lo switch del ruolo tramite rotta di debug -->
            <form action="{{ route('debug.switch-role', $role['id']) }}" method="POST">
                @csrf
                <button type="submit" class="w-full glass-card p-6 text-center hover:scale-105 transition group">
                    <!-- Icona del ruolo con colore dinamico -->
                    <div class="w-12 h-12 {{ $role['color'] }} rounded-xl mx-auto mb-4 flex items-center justify-center text-white shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-bold">{{ $role['name'] }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $role['desc'] }}</p>
                </button>
            </form>
            @endforeach
        </div>

        <div class="mt-12">
            <a href="/" class="text-slate-500 hover:text-white transition flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Annulla e torna alla Home
            </a>
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endsection
