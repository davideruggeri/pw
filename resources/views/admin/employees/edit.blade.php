@extends('layouts.dashboard')

@section('title', 'Modifica Dipendente: ' . $employee->Nome)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-forms.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="admin-form-container animate-fade-in">
    <div class="form-header">
        <a href="{{ route('employees.index') }}" class="back-btn-box">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter">Modifica Profilo</h3>
            <p class="text-sm text-slate-500">Aggiorna le informazioni di {{ $employee->Nome }}.</p>
        </div>
    </div>

    <div class="admin-form-card">
        <form action="{{ route('employees.update', $employee->Matricola) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="admin-label opacity-50">Matricola (Sola Lettura)</label>
                    <input type="text" value="{{ $employee->Matricola }}" disabled class="admin-input bg-slate-100 dark:bg-slate-900 cursor-not-allowed">
                </div>
                <div></div>

                <div>
                    <label class="admin-label opacity-50">Nome (Sola Lettura)</label>
                    <input type="text" value="{{ $employee->Nome }}" readonly class="admin-input bg-slate-100 dark:bg-slate-900 cursor-not-allowed">
                </div>
                <div>
                    <label class="admin-label opacity-50">Cognome (Sola Lettura)</label>
                    <input type="text" value="{{ $employee->Cognome }}" readonly class="admin-input bg-slate-100 dark:bg-slate-900 cursor-not-allowed">
                </div>

                <div>
                    <label class="admin-label">Reparto</label>
                    <select name="IDReparto_FK" id="reparto-select" required class="admin-input cursor-pointer">
                        @foreach($reparti as $reparto)
                            <option value="{{ $reparto->IDReparto }}" {{ old('IDReparto_FK', $employee->IDReparto_FK) == $reparto->IDReparto ? 'selected' : '' }}>
                                {{ $reparto->NomeReparto }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-50 dark:border-slate-800/50">
                <a href="{{ route('employees.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Annulla</a>
                <button type="submit" class="btn-premium px-10 py-4 shadow-xl">
                    Salva Modifiche
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
