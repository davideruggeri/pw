<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reparto extends Model
{
    protected $table = 'reparto';
    protected $primaryKey = 'IDReparto';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'NomeReparto',
        'ha_fatturato',
        'IDResponsabile_FK',
    ];

    public function responsabile()
    {
        return $this->belongsTo(Dipendente::class, 'IDResponsabile_FK', 'Matricola');
    }

    public function dipendenti()
    {
        return $this->hasMany(Dipendente::class, 'IDReparto_FK', 'IDReparto');
    }
}
