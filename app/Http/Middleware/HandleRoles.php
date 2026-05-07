<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->effective_role, $roles)) {
            // Se l'utente non ha il ruolo richiesto in base al reparto, redirect alla home
            return redirect('/')->with('error', 'Non hai i permessi necessari per accedere a questa sezione.');
        }

        return $next($request);
    }
}
