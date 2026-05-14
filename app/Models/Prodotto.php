<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    protected $table      = 'prodotto';
    protected $primaryKey = 'CodiceUnivoco';
    public    $incrementing = false;       // PK stringa, non auto-increment
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'CodiceUnivoco',
        'Descrizione',
        'QuantitaGiacenza',
        'ScortaMinima',
        'PrezzoVendita',
        'CostoProduzione',
        'IDCategoria_FK',
    ];

    /**
     * Accessor per NomeProdotto (mappa su Descrizione)
     */
    public function getNomeProdottoAttribute()
    {
        return $this->Descrizione;
    }

    public function getGiacenzaAttribute()
    {
        return $this->QuantitaGiacenza;
    }

    public function setGiacenzaAttribute($value)
    {
        $this->attributes['QuantitaGiacenza'] = $value;
    }

    public function getPrezzoListinoAttribute()
    {
        return $this->PrezzoVendita;
    }

    // ── Relazioni ─────────────────────────────────────────────
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'IDCategoria_FK', 'IDCategoria');
    }

    public function dettagliVendita()
    {
        return $this->hasMany(DettaglioVendita::class, 'CodiceUnivoco_FK', 'CodiceUnivoco');
    }

    /**
     * Relazione N:M verso OrdineVendita tramite DettaglioVendita.
     */
    public function ordiniVendita()
    {
        return $this->belongsToMany(
            OrdineVendita::class,
            'dettaglio_vendita',       // tabella pivot
            'CodiceUnivoco_FK',        // FK di questo modello nella pivot
            'IDOrdineVendita_FK'       // FK del modello target nella pivot
        )->withPivot(['QuantitaRichiesta', 'PrezzoApplicato']);
    }

    public function preferitiDaiClienti()
    {
        return $this->belongsToMany(Cliente::class, 'preferiti', 'CodiceUnivoco_FK', 'CodiceCliente_FK')
                    ->withTimestamps();
    }

    public function forniture()
    {
        return $this->hasMany(Fornitura::class, 'CodiceUnivoco_FK', 'CodiceUnivoco');
    }

    public function fornitori()
    {
        return $this->belongsToMany(
            Fornitore::class,
            'fornitura',
            'CodiceUnivoco_FK',
            'PartitaIVA_FK'
        )->withPivot('PrezzoAcquistoSpecifico');
    }

    public function ordiniAcquisto()
    {
        return $this->belongsToMany(
            OrdineAcquisto::class,
            'dettaglio_acquisto',
            'CodiceUnivoco_FK',
            'IDOrdineAcquisto_FK'
        )->withPivot(['QuantitaOrdinata', 'PrezzoPattuito']);
    }
}
