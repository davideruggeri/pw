<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualitaLog extends Model
{
    protected $table = 'qualita_log';
    protected $primaryKey = 'IDLogQualita';
    public $timestamps = false;

    protected $fillable = [
        'IDLogProduzione_FK',
        'QuantitaScartata',
        'Esito',
        'NoteDifetto',
        'DataControllo'
    ];

    public function produzione()
    {
        return $this->belongsTo(ProduzioneLog::class, 'IDLogProduzione_FK', 'IDLogProduzione');
    }
}
