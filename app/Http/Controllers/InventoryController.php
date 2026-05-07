<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    // ────────────────────────────────────────────────────────────
    // 1. Prodotti Sotto Scorta
    // ────────────────────────────────────────────────────────────

    /**
     * Restituisce i prodotti la cui giacenza è inferiore alla scorta minima,
     * aggiungendo il campo calcolato QuantitaDaOrdinare.
     *
     * SELECT *, (ScortaMinima - QuantitaGiacenza) AS QuantitaDaOrdinare
     * FROM prodotto
     * WHERE QuantitaGiacenza < ScortaMinima
     * ORDER BY QuantitaDaOrdinare DESC
     */
    public function prodottiSottoScorta(): JsonResponse
    {
        $prodotti = Prodotto::query()
            ->selectRaw('*, (ScortaMinima - QuantitaGiacenza) AS QuantitaDaOrdinare')
            ->whereColumn('QuantitaGiacenza', '<', 'ScortaMinima')
            ->orderByDesc('QuantitaDaOrdinare')
            ->get();

        return response()->json([
            'count' => $prodotti->count(),
            'data'  => $prodotti,
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // 2. Fatturato Totale per Cliente
    // ────────────────────────────────────────────────────────────

    /**
     * Calcola il fatturato totale generato da ciascun cliente.
     *
     * SELECT
     *   c.CodiceCliente,
     *   c.RagioneSociale,
     *   SUM(dv.QuantitaRichiesta * dv.PrezzoApplicato) AS FatturatoTotale
     * FROM cliente c
     *   JOIN ordine_vendita ov ON ov.CodiceCliente_FK = c.CodiceCliente
     *   JOIN dettaglio_vendita dv ON dv.IDOrdineVendita_FK = ov.IDOrdineVendita
     * GROUP BY c.CodiceCliente, c.RagioneSociale
     * ORDER BY FatturatoTotale DESC
     */
    public function fatturatoPerCliente(): JsonResponse
    {
        $risultati = DB::table('cliente AS c')
            ->join('ordine_vendita AS ov', 'ov.CodiceCliente_FK', '=', 'c.CodiceCliente')
            ->join('dettaglio_vendita AS dv', 'dv.IDOrdineVendita_FK', '=', 'ov.IDOrdineVendita')
            ->select([
                'c.CodiceCliente',
                'c.RagioneSociale',
                DB::raw('SUM(dv.QuantitaRichiesta * dv.PrezzoApplicato) AS FatturatoTotale'),
            ])
            ->groupBy('c.CodiceCliente', 'c.RagioneSociale')
            ->orderByDesc('FatturatoTotale')
            ->get();

        return response()->json([
            'count' => $risultati->count(),
            'data'  => $risultati,
        ]);
    }
}
