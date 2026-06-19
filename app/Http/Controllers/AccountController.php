<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $dipendente = $user ? $user->dipendente : null;

        // Se veniamo da una pagina esterna all'account, salviamola come punto di ritorno
        $previousUrl = url()->previous();
        if ($previousUrl && 
            !str_contains($previousUrl, '/account') && 
            !str_contains($previousUrl, '/login') && 
            !str_contains($previousUrl, '/register') && 
            $previousUrl !== url()->current()
        ) {
            session(['account_back_url' => $previousUrl]);
        }

        $backUrl = session('account_back_url', route('home'));

        return view('account.index', compact('user', 'dipendente', 'backUrl'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'Il campo password è obbligatorio.',
            'password.min'       => 'La password deve contenere almeno 8 caratteri.',
            'password.confirmed' => 'Le due password inserite non corrispondono.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed' => true,
        ]);

        $backUrl = session('account_back_url', route('home'));
        return redirect()->to($backUrl)->with('success', 'Password aggiornata con successo!');
    }
}
