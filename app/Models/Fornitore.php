<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornitore extends Model
{
    protected $table      = 'fornitore';
    protected $primaryKey = 'PartitaIVA';
    public    $incrementing = false;
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'PartitaIVA',
        'RagioneSociale',
        'Contatti',
    ];

    // ── Relazioni ─────────────────────────────────────────────
    public function forniture()
    {
        return $this->hasMany(Fornitura::class, 'PartitaIVA_FK', 'PartitaIVA');
    }

    public function prodotti()
    {
        return $this->belongsToMany(
            Prodotto::class,
            'fornitura',
            'PartitaIVA_FK',
            'CodiceUnivoco_FK'
        )->withPivot('PrezzoAcquistoSpecifico');
    }

    public function ordiniAcquisto()
    {
        return $this->hasMany(OrdineAcquisto::class, 'PartitaIVA_FK', 'PartitaIVA');
    }
}
