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
        $recentOrders = $this->ordineRepo->getRecent(5);

        // Statistiche Reparti per l'Admin
        $deptStats = [
            'production' => \App\Models\ProduzioneLog::whereMonth('DataProduzione', now()->month)->sum('QuantitaProdotta'),
            'maintenance' => \App\Models\ManutenzioneLog::whereMonth('DataIntervento', now()->month)->sum('OreFermoMacchina'),
            'quality' => $kpis['totalRevenue'] > 0 ? ($kpis['qualityLosses'] / $kpis['totalRevenue']) * 100 : 0,
            'logistics' => $kpis['lowStockCount']
        ];

        return view('admin.dashboard', array_merge($kpis, [
            'recentOrders' => $recentOrders,
            'employeeStats' => $employeeStats,
            'customerStats' => $customerStats,
            'deptStats' => $deptStats
        ]));
    }

    public function sales()
    {
        $salesData = DB::table('dettaglio_vendita')
            ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
            ->join('prodotto', 'dettaglio_vendita.CodiceUnivoco_FK', '=', 'prodotto.CodiceUnivoco')
            ->whereMonth('ordine_vendita.Data', now()->month)
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

    // Dashboard Logistica: monitoraggio giacenze, valore magazzino e prodotti sotto scorta
    public function logistics()
    {
        $products = DB::table('prodotto')->get();

        $totalProducts = $products->count();
        $totalWarehouseValue = $products->sum(fn($p) => $p->QuantitaGiacenza * $p->CostoProduzione);

        $sottoScorta = $products->filter(fn($p) => $p->QuantitaGiacenza <= $p->ScortaMinima);
        $sottoScortaCount = $sottoScorta->count();

        $inventoryStatus = [
            'critical' => $products->filter(fn($p) => $p->QuantitaGiacenza == 0)->count(),
            'warning' => $sottoScortaCount,
            'ok' => $totalProducts - $sottoScortaCount
        ];

        return view('logistics.dashboard', compact(
            'totalProducts',
            'totalWarehouseValue',
            'sottoScorta',
            'sottoScortaCount',
            'inventoryStatus'
        ));
    }

    public function operations()
    {
        $stats = DB::table('produzione_log')
            ->selectRaw('SUM(QuantitaProdotta) as total_qty, SUM(CostoEnergiaStimato) as total_energy')
            ->whereMonth('DataProduzione', now()->month)
            ->first();

        $recentLogs = \App\Models\ProduzioneLog::with('prodotto', 'responsabile')
            ->orderByDesc('DataProduzione')
            ->limit(10)
            ->get();

        $productionByDay = DB::table('produzione_log')
            ->selectRaw('DATE(DataProduzione) as date, SUM(QuantitaProdotta) as qty')
            ->where('DataProduzione', '>=', now()->subDays(14))
            ->groupBy('date')
            ->get();

        // Metriche Qualità
        $qualityStats = DB::table('qualita_log')
            ->join('produzione_log', 'qualita_log.IDLogProduzione_FK', '=', 'produzione_log.IDLogProduzione')
            ->selectRaw('SUM(QuantitaProdotta) as total_produced, SUM(QuantitaScartata) as total_rejected')
            ->whereMonth('DataControllo', now()->month)
            ->first();

        $rejectionRate = ($qualityStats->total_produced > 0)
            ? ($qualityStats->total_rejected / $qualityStats->total_produced) * 100
            : 0;

        $qualityIssues = \App\Models\QualitaLog::with('produzione.prodotto')
            ->where('Esito', 'FAIL')
            ->orderByDesc('DataControllo')
            ->limit(5)
            ->get();

        // Metriche Manutenzione
        $maintenanceStats = DB::table('manutenzione_log')
            ->selectRaw('SUM(OreFermoMacchina) as total_downtime, SUM(CostoRicambi) as total_maintenance_cost')
            ->whereMonth('DataIntervento', now()->month)
            ->first();

        $recentMaintenance = \App\Models\ManutenzioneLog::with('tecnico')
            ->orderByDesc('DataIntervento')
            ->limit(3)
            ->get();

        return view('operations.dashboard', [
            'totalQty' => $stats->total_qty ?? 0,
            'totalEnergy' => $stats->total_energy ?? 0,
            'recentLogs' => $recentLogs,
            'productionByDay' => $productionByDay,
            'rejectionRate' => $rejectionRate,
            'qualityIssues' => $qualityIssues,
            'totalDowntime' => $maintenanceStats->total_downtime ?? 0,
            'totalMaintenanceCost' => $maintenanceStats->total_maintenance_cost ?? 0,
            'recentMaintenance' => $recentMaintenance
        ]);
    }

    // Dashboard Cliente: riepilogo ordini, preferiti e prodotti più venduti (bestsellers)
    public function customer()
    {
        $user = auth()->user();
        $cliente = $user ? $user->cliente : null;

        $stats = [
            'ordini_count' => $cliente ? $cliente->ordiniVendita()->count() : 0,
            'preferiti_count' => $cliente ? $cliente->preferiti()->count() : 0,
            'totale_speso' => $cliente ? $cliente->ordiniVendita->sum(fn($o) => $o->totale_ordine) : 0,
        ];

        $bestsellers = \App\Models\Prodotto::withSum('dettagliVendita as total_sold', 'QuantitaRichiesta')
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
