<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    // Mostra la vista con la griglia di selezione dei ruoli
    public function showRoleSelector()
    {
        return view('auth.role-selector');
    }

    /* 
       Cambia il ruolo dell'utente loggato. 
       NOTA: Per sicurezza, questa funzione è limitata a un'email specifica.
    */
    public function switchRole($role)
    {
        $validRoles = ['admin', 'sales', 'logistics', 'production', 'customer'];
        
        // Verifica che il ruolo richiesto sia tra quelli gestiti dal sistema
        if (!in_array($role, $validRoles)) {
            return back()->with('error', 'Ruolo non valido.');
        }

        $user = Auth::user();
        
        // Controllo di sicurezza: solo l'admin di sistema può usare lo switcher
        if ($user->email !== 'admin@azienda.it') {
            return back()->with('error', 'Azione non autorizzata.');
        }

        // Aggiorna il campo 'role' nella tabella users e salva
        $user->role = $role;
        $user->save();

        return redirect('/')->with('success', "Ruolo cambiato in: $role");
    }
}
