<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManutenzioneLog extends Model
{
    protected $table = 'manutenzione_log';
    protected $primaryKey = 'IDLogManutenzione';
    public $timestamps = false;

    protected $fillable = [
        'TipoIntervento',
        'OreFermoMacchina',
        'CostoRicambi',
        'DataIntervento',
        'Matricola_FK'
    ];

    public function tecnico()
    {
        return $this->belongsTo(Dipendente::class, 'Matricola_FK', 'Matricola');
    }
}
