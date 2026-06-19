<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showCustomerLoginForm()
    {
        return view('auth.login-customer');
    }

    public function showStaffLoginForm()
    {
        return view('auth.login-staff');
    }

    public function login(Request $request)
    {
        $isCustomerField = $request->has('email');
        $loginValue = $request->input('login') ?? $request->input('email');
        $errorKey = $isCustomerField ? 'email' : 'login';

        $request->validate([
            'password' => ['required'],
        ]);

        if (!$loginValue) {
            throw ValidationException::withMessages([
                $errorKey => 'Inserisci le tue credenziali.',
            ]);
        }

        // Determiniamo il campo di login (Email, Matricola o Codice Cliente)
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'email';
        } elseif (is_numeric($loginValue)) {
            $loginField = 'matricola_fk';
        } else {
            $loginField = 'codice_cliente_fk';
        }

        $credentials = [
            $loginField => $loginValue,
            'password'  => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        throw ValidationException::withMessages([
            $errorKey => __('Le credenziali fornite non sono corrette.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectBasedOnRole($user)
    {
        if (!$user->password_changed) {
            // Determina la destinazione finale in base al ruolo dell'utente
            $finalDestination = match ($user->effective_role) {
                'admin'     => '/admin/dashboard',
                'sales'     => '/sales/dashboard',
                'logistics' => '/logistics',
                default     => '/customer/dashboard',
            };

            // Salva in sessione in modo che AccountController sappia dove reindirizzare l'utente dopo il reset
            session(['account_back_url' => $finalDestination]);

            return redirect()->route('account.index')->with('warning', 'Devi cambiare la password al primo accesso.');
        }

        return match ($user->effective_role) {
            'admin'     => redirect()->intended('/admin/dashboard'),
            'sales'     => redirect()->intended('/sales/dashboard'),
            'logistics' => redirect()->intended('/logistics'),
            default     => redirect()->intended('/customer/dashboard'),
        };
    }
}
