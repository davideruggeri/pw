<?php

namespace App\Services;

use App\Repositories\Interfaces\DipendenteRepositoryInterface;
use App\Repositories\Interfaces\OrdineRepositoryInterface;
use App\Repositories\Interfaces\ProdottoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    protected $dipendenteRepo;
    protected $ordineRepo;
    protected $prodottoRepo;

    public function __construct(
        DipendenteRepositoryInterface $dipendenteRepo,
        OrdineRepositoryInterface $ordineRepo,
        ProdottoRepositoryInterface $prodottoRepo
    ) {
        $this->dipendenteRepo = $dipendenteRepo;
        $this->ordineRepo = $ordineRepo;
        $this->prodottoRepo = $prodottoRepo;
    }

    public function getAdminKpis()
    {
        $salesData = DB::table('dettaglio_vendita')
            ->join('prodotto', 'dettaglio_vendita.CodiceUnivoco_FK', '=', 'prodotto.CodiceUnivoco')
            ->selectRaw('
                SUM(QuantitaRichiesta * PrezzoApplicato) as revenue,
                SUM(QuantitaRichiesta * CostoProduzione) as cogs
            ')->first();

        $energyCosts = DB::table('produzione_log')->sum('CostoEnergiaStimato');
        $maintenanceCosts = DB::table('manutenzione_log')->sum('CostoRicambi');
        $replenishmentCosts = DB::table('movimenti_magazzino')->where('Tipo', 'carico')->sum('CostoTotale');
        
        $qualityLosses = DB::table('qualita_log')
            ->join('produzione_log', 'qualita_log.IDLogProduzione_FK', '=', 'produzione_log.IDLogProduzione')
            ->join('prodotto', 'produzione_log.CodiceUnivoco_FK', '=', 'prodotto.CodiceUnivoco')
            ->selectRaw('SUM(QuantitaScartata * CostoProduzione) as loss')
            ->value('loss') ?? 0;
 
        $totalEmployees = DB::table('dipendente')->count();
        $laborCosts = $totalEmployees * 3500; // Media costo aziendale mensile per dipendente
 
        $totalRevenue = $salesData->revenue ?? 0;
        $cogs = $salesData->cogs ?? 0;
        
        $ebitda = $totalRevenue - $cogs - $energyCosts - $maintenanceCosts - $qualityLosses - $laborCosts - $replenishmentCosts;
 
        return [
            'totalRevenue' => $totalRevenue,
            'cogs' => $cogs,
            'energyCosts' => $energyCosts,
            'maintenanceCosts' => $maintenanceCosts,
            'replenishmentCosts' => $replenishmentCosts,
            'qualityLosses' => $qualityLosses,
            'laborCosts' => $laborCosts,
            'ebitda' => $ebitda,
            'activeOrders' => $this->ordineRepo->getActiveCount(),
            'totalEmployees' => $totalEmployees,
            'lowStockCount' => count($this->prodottoRepo->getLowStock()),
        ];
    }

    public function getEmployeePerformance()
    {
        return DB::table('dipendente')
            ->leftJoin('ordine_vendita', 'dipendente.Matricola', '=', 'ordine_vendita.Matricola_FK')
            ->leftJoin('dettaglio_vendita', 'ordine_vendita.IDOrdineVendita', '=', 'dettaglio_vendita.IDOrdineVendita_FK')
            ->select(
                'dipendente.Matricola',
                'dipendente.Nome',
                'dipendente.Cognome',
                DB::raw('COUNT(DISTINCT ordine_vendita.IDOrdineVendita) as total_orders'),
                DB::raw('SUM(dettaglio_vendita.QuantitaRichiesta * dettaglio_vendita.PrezzoApplicato) as total_revenue')
            )
            ->groupBy('dipendente.Matricola', 'dipendente.Nome', 'dipendente.Cognome')
            ->orderByDesc('total_revenue')
            ->get();
    }

    public function getTopCustomers()
    {
        return DB::table('cliente')
            ->join('ordine_vendita', 'cliente.CodiceCliente', '=', 'ordine_vendita.CodiceCliente_FK')
            ->join('dettaglio_vendita', 'ordine_vendita.IDOrdineVendita', '=', 'dettaglio_vendita.IDOrdineVendita_FK')
            ->select(
                'cliente.CodiceCliente',
                'cliente.Nome',
                DB::raw('SUM(dettaglio_vendita.QuantitaRichiesta * dettaglio_vendita.PrezzoApplicato) as revenue')
            )
            ->groupBy('cliente.CodiceCliente', 'cliente.Nome')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
    }
}
