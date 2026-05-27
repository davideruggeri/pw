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
            ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
            ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito'])
            ->selectRaw('SUM(QuantitaRichiesta * PrezzoApplicato) as revenue')
            ->first();

        $totalRevenue = $salesData->revenue ?? 0;
        $pendingOrdersCount = DB::table('ordine_vendita')->where('Stato', 'In Attesa')->count();
        $totalCustomers = DB::table('cliente')->count();
        $totalEmployees = DB::table('dipendente')->count();
        $lowStockCount = count($this->prodottoRepo->getLowStock());

        return [
            'totalRevenue' => $totalRevenue,
            'pendingOrdersCount' => $pendingOrdersCount,
            'totalCustomers' => $totalCustomers,
            'totalEmployees' => $totalEmployees,
            'lowStockCount' => $lowStockCount,
        ];
    }

    public function getEmployeePerformance()
    {
        return DB::table('dipendente')
            ->leftJoin('ordine_vendita', function($join) {
                $join->on('dipendente.Matricola', '=', 'ordine_vendita.Matricola_FK')
                     ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito']);
            })
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
            ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito'])
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

    public function getBestsellers($limit = 5)
    {
        return DB::table('prodotto')
            ->join('dettaglio_vendita', 'prodotto.CodiceUnivoco', '=', 'dettaglio_vendita.CodiceUnivoco_FK')
            ->join('ordine_vendita', 'dettaglio_vendita.IDOrdineVendita_FK', '=', 'ordine_vendita.IDOrdineVendita')
            ->whereIn('ordine_vendita.Stato', ['Approvato', 'Spedito'])
            ->select(
                'prodotto.CodiceUnivoco',
                'prodotto.Descrizione as NomeProdotto',
                DB::raw('SUM(dettaglio_vendita.QuantitaRichiesta) as total_sold'),
                DB::raw('SUM(dettaglio_vendita.QuantitaRichiesta * dettaglio_vendita.PrezzoApplicato) as revenue')
            )
            ->groupBy('prodotto.CodiceUnivoco', 'prodotto.Descrizione')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}
