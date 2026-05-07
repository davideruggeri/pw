<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->password_changed) {
            // Se non ha cambiato la password e non è già sulla pagina di cambio password o prelobby
            if (!$request->is('account*') && !$request->is('logout') && !$request->is('/')) {
                return redirect()->route('account.index')->with('warning', 'Devi cambiare la password al primo accesso.');
            }
        }

        return $next($request);
    }
}
