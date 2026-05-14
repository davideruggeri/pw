<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use App\Models\Cliente;
use App\Models\Dipendente;
use App\Models\Reparto;
use Illuminate\Support\Facades\DB;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        // Se l'utente è loggato E ha già cambiato la password, lo mandiamo alla sua dashboard
        if (auth()->check() && auth()->user()->password_changed) {
            return match (auth()->user()->effective_role) {
                'admin'     => redirect('/admin/dashboard'),
                'sales'     => redirect('/sales/dashboard'),
                'logistics' => redirect('/logistics'),
                'customer'  => redirect(route('customer.dashboard')),
                default     => view('prelobby', ['stats' => $this->getStats()]),
            };
        }

        // Se è un ospite O un utente che deve ancora cambiare password, vede la Prelobby
        $stats = $this->getStats();
        $bestsellers = \App\Models\Prodotto::withSum('dettagliVendita as total_sold', 'QuantitaRichiesta')
            ->orderByDesc('total_sold')
            ->limit(3)
            ->get();

        return view('prelobby', compact('stats', 'bestsellers'));
    }

    private function getStats(): array
    {
        $stats = [
            'clienti' => 0,
            'staff'   => 0,
            'reparti' => 0
        ];

        try {
            $stats['clienti'] = \App\Models\Cliente::count();
            $stats['staff']   = \App\Models\Dipendente::count();
            $stats['reparti'] = \App\Models\Reparto::count();
        } catch (\Throwable $e) {
            // Silenzioso o log error
        }

        return $stats;
    }
}
