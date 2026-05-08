<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdineVendita extends Model
{
    protected $table      = 'ordine_vendita';
    protected $primaryKey = 'IDOrdineVendita';
    public    $incrementing = true;        // INT auto-increment
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = [
        'IDOrdineVendita',
        'Data',
        'Stato',
        'CodiceCliente_FK',
        'Matricola_FK',
    ];


    // ── Relazioni ─────────────────────────────────────────────
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'CodiceCliente_FK', 'CodiceCliente');
    }

    public function dettagliVendita()
    {
        return $this->hasMany(DettaglioVendita::class, 'IDOrdineVendita_FK', 'IDOrdineVendita');
    }

    public function venditore()
    {
        return $this->belongsTo(Dipendente::class, 'Matricola_FK', 'Matricola');
    }

    /**
     * Relazione N:M verso Prodotto tramite DettaglioVendita.
     */
    public function prodotti()
    {
        return $this->belongsToMany(
            Prodotto::class,
            'dettaglio_vendita',       // tabella pivot
            'IDOrdineVendita_FK',      // FK di questo modello nella pivot
            'CodiceUnivoco_FK'         // FK del modello target nella pivot
        )->withPivot(['QuantitaRichiesta', 'PrezzoApplicato']);
    }
    public function getTotaleOrdineAttribute()
    {
        return $this->dettagliVendita->sum(function ($d) {
            return $d->QuantitaRichiesta * $d->PrezzoApplicato;
        });
    }
}
