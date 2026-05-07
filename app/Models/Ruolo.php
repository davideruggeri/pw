<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruolo extends Model
{
    protected $table = 'ruolo';
    protected $primaryKey = 'IDRuolo';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'NomeRuolo',
        'Livello',
    ];

    public function dipendenti()
    {
        return $this->hasMany(Dipendente::class, 'IDRuolo_FK', 'IDRuolo');
    }
}
