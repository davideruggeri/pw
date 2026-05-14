<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabella associativa che definisce il catalogo/prezzi di acquisto per fornitore e prodotto.
 * PK composta: (PartitaIVA_FK, CodiceUnivoco_FK)
 */
class Fornitura extends Model
{
    protected $table      = 'fornitura';
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $fillable = [
        'PartitaIVA_FK',
        'CodiceUnivoco_FK',
        'PrezzoAcquistoSpecifico',
    ];

    // ── Relazioni ─────────────────────────────────────────────
    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class, 'PartitaIVA_FK', 'PartitaIVA');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'CodiceUnivoco_FK', 'CodiceUnivoco');
    }
}
