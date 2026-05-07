<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabella associativa N:M tra ordine_vendita e prodotto.
 * PK composta: (IDOrdineVendita_FK, CodiceUnivoco_FK)
 */
class DettaglioVendita extends Model
{
    protected $table      = 'dettaglio_vendita';
    // Nessuna singola PK — Eloquent usa la coppia di FK come chiave logica.
    // Se la tabella ha un ID surrogato, impostare $primaryKey di conseguenza.
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $fillable = [
        'IDOrdineVendita_FK',
        'CodiceUnivoco_FK',
        'QuantitaRichiesta',
        'PrezzoApplicato',
    ];

    // ── Relazioni ─────────────────────────────────────────────
    public function ordineVendita()
    {
        return $this->belongsTo(OrdineVendita::class, 'IDOrdineVendita_FK', 'IDOrdineVendita');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'CodiceUnivoco_FK', 'CodiceUnivoco');
    }
}
