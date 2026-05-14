<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdineAcquisto extends Model
{
    protected $table      = 'ordine_acquisto';
    protected $primaryKey = 'IDOrdineAcquisto';
    public    $incrementing = true;
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = [
        'IDOrdineAcquisto',
        'Data',
        'Stato',
        'PartitaIVA_FK',
        'Matricola_FK',
    ];

    // ── Relazioni ─────────────────────────────────────────────
    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class, 'PartitaIVA_FK', 'PartitaIVA');
    }

    public function dettagliAcquisto()
    {
        return $this->hasMany(DettaglioAcquisto::class, 'IDOrdineAcquisto_FK', 'IDOrdineAcquisto');
    }

    public function buyer()
    {
        return $this->belongsTo(Dipendente::class, 'Matricola_FK', 'Matricola');
    }

    /**
     * Relazione N:M verso Prodotto tramite DettaglioAcquisto.
     */
    public function prodotti()
    {
        return $this->belongsToMany(
            Prodotto::class,
            'dettaglio_acquisto',
            'IDOrdineAcquisto_FK',
            'CodiceUnivoco_FK'
        )->withPivot(['QuantitaOrdinata', 'PrezzoPattuito']);
    }

    public function getTotaleOrdineAttribute()
    {
        return $this->dettagliAcquisto->sum(function ($d) {
            return $d->QuantitaOrdinata * $d->PrezzoPattuito;
        });
    }
}
