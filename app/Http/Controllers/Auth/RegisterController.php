<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]+$/'
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
        ], [
            'email.regex' => 'L\'email deve essere nel formato parola@parola.parola (es. mario@rossi.it) senza altri punti.',
        ]);

        // Genera un Codice Cliente unico (es: CLI + Timestamp)
        $codiceCliente = 'CLI' . time();

        $cliente = Cliente::create([
            'CodiceCliente' => $codiceCliente,
            'Nome' => $request->name,
            'IndirizzoSpedizione' => $request->address,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'codice_cliente_fk' => $codiceCliente,
            'password_changed' => true, // I clienti scelgono la password subito
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }
}
