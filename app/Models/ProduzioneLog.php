<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduzioneLog extends Model
{
    protected $table = 'produzione_log';
    protected $primaryKey = 'IDLogProduzione';
    public $timestamps = false; // Usiamo DataProduzione custom

    protected $fillable = [
        'CodiceUnivoco_FK',
        'QuantitaProdotta',
        'Matricola_FK',
        'CostoEnergiaStimato',
        'DataProduzione'
    ];

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'CodiceUnivoco_FK', 'CodiceUnivoco');
    }

    public function responsabile()
    {
        return $this->belongsTo(Dipendente::class, 'Matricola_FK', 'Matricola');
    }

    public function qualita()
    {
        return $this->hasOne(QualitaLog::class, 'IDLogProduzione_FK', 'IDLogProduzione');
    }
}
