@extends('layouts.dashboard')

@section('title', 'Nuovo Dipendente')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-forms.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="admin-form-container animate-fade-in">
    <div class="admin-form-card">
        <div class="form-header">
            <a href="{{ route('employees.index') }}" class="back-btn-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter">Aggiungi Collaboratore</h3>
        </div>

        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="admin-label">Matricola</label>
                    <input type="number" name="Matricola" value="{{ old('Matricola') }}" class="admin-input @error('Matricola') border-red-500 @enderror" placeholder="es. 5001" required>
                    @error('Matricola') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="admin-label">Nome</label>
                    <input type="text" name="Nome" value="{{ old('Nome') }}" class="admin-input" required>
                </div>
                <div>
                    <label class="admin-label">Cognome</label>
                    <input type="text" name="Cognome" value="{{ old('Cognome') }}" class="admin-input" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="admin-label">Reparto</label>
                    <select name="IDReparto_FK" class="admin-input cursor-pointer" required>
                        <option value="">-- Seleziona --</option>
                        @foreach($reparti as $rep)
                            <option value="{{ $rep->IDReparto }}">{{ $rep->NomeReparto }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-50 dark:border-slate-800/50">
                <button type="submit" class="btn-premium px-10 py-4 shadow-xl">Salva Dipendente</button>
            </div>
        </form>
    </div>
</div>
@endsection
