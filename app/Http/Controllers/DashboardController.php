<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\OrdineRepositoryInterface;
use App\Repositories\Interfaces\ProdottoRepositoryInterface;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $ordineRepo;
    protected $prodottoRepo;
    protected $analytics;

    public function __construct(
        OrdineRepositoryInterface $ordineRepo,
        ProdottoRepositoryInterface $prodottoRepo,
        AnalyticsService $analytics
    ) {
        $this->ordineRepo = $ordineRepo;
        $this->prodottoRepo = $prodottoRepo;
        $this->analytics = $analytics;
    }

    // Dashboard Amministrativa: visualizza KPI globali, performance dipendenti e clienti top
    public function admin()
    {
        $kpis = $this->analytics->getAdminKpis();
        $employeeStats = $this->analytics->getEmployeePerformance();
        $customerStats = $this->analytics->getTopCustomers();
        $bestsellers = $this->analytics->getBestsellers(5);
        $recentOrders = $this->ordineRepo->getRecent(5);

        return view('admin.dashboard', array_merge($kpis, [
            'recentOrders' => $recentOrders,
            'employeeStats' => $employeeStats,
            'customerStats' => $customerStats,
            'bestsellers' => $bestsellers
        ]));
    }

    public function sales()
    {
        $salesData = DB::table('dettaglio_vendita')
            ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
            ->join('prodotto', 'dettaglio_vendita.CodiceUnivoco_FK', '=', 'prodotto.CodiceUnivoco')
            ->whereMonth('ordine_vendita.Data', now()->month)
            ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito'])
            ->selectRaw('
                SUM(QuantitaRichiesta * PrezzoApplicato) as revenue,
                SUM(QuantitaRichiesta * CostoProduzione) as cost
            ')
            ->first();

        $monthlyRevenue = $salesData->revenue ?? 0;
        $monthlyCost = $salesData->cost ?? 0;
        $monthlyMargin = $monthlyRevenue - $monthlyCost;

        $newClients = DB::table('cliente')->count();

        $topProducts = DB::table('prodotto')
            ->join('dettaglio_vendita', 'prodotto.CodiceUnivoco', '=', 'dettaglio_vendita.CodiceUnivoco_FK')
            ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
            ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito'])
            ->select(
                'prodotto.Descrizione as NomeProdotto',
                DB::raw('SUM(dettaglio_vendita.QuantitaRichiesta) as total_sold'),
                DB::raw('SUM(dettaglio_vendita.QuantitaRichiesta * (dettaglio_vendita.PrezzoApplicato - prodotto.CostoProduzione)) as profit')
            )
            ->groupBy('prodotto.Descrizione')
            ->orderByDesc('profit')
            ->limit(5)
            ->get();

        $recentOrders = \App\Models\OrdineVendita::with('cliente')
            ->orderBy('Data', 'desc')
            ->limit(5)
            ->get();

        $clienti = \App\Models\Cliente::all();

        return view('sales.dashboard', compact('monthlyRevenue', 'monthlyMargin', 'newClients', 'topProducts', 'recentOrders', 'clienti'));
    }



    // Dashboard Cliente: riepilogo ordini, preferiti e prodotti più venduti (bestsellers)
    public function customer()
    {
        $user = auth()->user();
        $cliente = $user ? $user->cliente : null;

        $stats = [
            'ordini_count' => $cliente ? $cliente->ordiniVendita()->count() : 0,
            'preferiti_count' => $cliente ? $cliente->preferiti()->count() : 0,
            'totale_speso' => $cliente ? $cliente->ordiniVendita()->whereIn('Stato', ['Approvato', 'Spedito'])->get()->sum(fn($o) => $o->totale_ordine) : 0,
        ];

        $bestsellers = \App\Models\Prodotto::withSum(['dettagliVendita as total_sold' => function($query) {
            $query->whereHas('ordineVendita', function($q) {
                $q->whereIn('Stato', ['Approvato', 'Spedito']);
            });
        }], 'QuantitaRichiesta')
            ->orderByDesc('total_sold')
            ->limit(4)
            ->get();

        $recentOrders = $cliente 
            ? $cliente->ordiniVendita()->orderBy('Data', 'desc')->limit(3)->get()
            : collect();

        return view('customer.dashboard', compact('user', 'cliente', 'stats', 'bestsellers', 'recentOrders'));
    }

    public function customerOrders(\Illuminate\Http\Request $request)
    {
        if (!auth()->check())
            return view('customer.guest_access');
        $user = auth()->user();
        $cliente = $user ? $user->cliente : null;
        
        $perPage = $request->get('per_page', 10);
        
        $ordini = $cliente 
            ? $cliente->ordiniVendita()->orderBy('Data', 'desc')->paginate($perPage)->withQueryString() 
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);

        return view('customer.orders_list', compact('user', 'cliente', 'ordini', 'perPage'));
    }

    public function customerFavorites()
    {
        if (!auth()->check())
            return view('customer.guest_access');
        $user = auth()->user();
        $cliente = $user ? $user->cliente : null;
        $preferiti = $cliente 
            ? $cliente->preferiti()->paginate(12) 
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);

        return view('customer.favorites_list', compact('user', 'cliente', 'preferiti'));
    }

    public function customerOrderShow($id)
    {
        if (!auth()->check())
            return view('customer.guest_access');
        
        $user = auth()->user();
        $cliente = $user ? $user->cliente : null;

        if (!$cliente) {
            abort(403, 'Profilo cliente non trovato.');
        }
        
        $ordine = \App\Models\OrdineVendita::with(['dettagliVendita.prodotto.categoria'])
            ->where('IDOrdineVendita', $id)
            ->where('CodiceCliente_FK', $cliente->CodiceCliente)
            ->firstOrFail();
            
        return view('customer.order_detail', compact('user', 'cliente', 'ordine'));
    }

    public function customerCart()
    {
        $user = auth()->user();
        $cliente = $user ? $user->cliente : null;
        return view('customer.cart', compact('user', 'cliente'));
    }
}
