@extends('layouts.dashboard')

@section('title', 'Gestione Organico')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/employees-list.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="animate-fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">Personale Aziendale</h3>
            <p class="text-sm text-slate-500">Gestisci le anagrafiche e i ruoli del personale aziendale.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="btn-premium flex items-center gap-2 px-6 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nuovo Dipendente
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800 rounded-xl flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="employee-table-container">
        <div class="overflow-x-auto">
            <table class="employee-table">
                <thead>
                    <tr>
                        <th>Matricola</th>
                        <th>Nominativo</th>
                        <th>Reparto</th>
                        <th>Ruolo</th>
                        <th class="text-right">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($employees as $emp)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition group">
                        <td class="font-mono text-xs text-slate-500 font-bold">#{{ $emp->Matricola }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="employee-avatar">
                                    {{ substr($emp->Nome, 0, 1) }}{{ substr($emp->Cognome, 0, 1) }}
                                </div>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $emp->Nome }} {{ $emp->Cognome }}</p>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="badge-reparto">
                                    {{ $emp->reparto->NomeReparto ?? 'N/D' }}
                                </span>
                                @if($emp->reparto && $emp->reparto->IDResponsabile_FK == $emp->Matricola)
                                    <span class="badge-responsabile">Responsabile</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $emp->ruolo->NomeRuolo ?? 'N/D' }}</span>
                                @if($emp->ruolo && $emp->ruolo->Livello >= 2)
                                    <span class="text-[9px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-tight">Livello {{ $emp->ruolo->Livello }} ★</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('employees.edit', $emp->Matricola) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <form action="{{ route('employees.destroy', $emp->Matricola) }}" method="POST" onsubmit="return confirm('Sei sicuro?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
