<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabella associativa N:M tra ordine_acquisto e prodotto.
 * PK composta: (IDOrdineAcquisto_FK, CodiceUnivoco_FK)
 */
class DettaglioAcquisto extends Model
{
    protected $table      = 'dettaglio_acquisto';
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $fillable = [
        'IDOrdineAcquisto_FK',
        'CodiceUnivoco_FK',
        'QuantitaOrdinata',
        'PrezzoPattuito',
    ];

    // ── Relazioni ─────────────────────────────────────────────
    public function ordineAcquisto()
    {
        return $this->belongsTo(OrdineAcquisto::class, 'IDOrdineAcquisto_FK', 'IDOrdineAcquisto');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'CodiceUnivoco_FK', 'CodiceUnivoco');
    }
}
