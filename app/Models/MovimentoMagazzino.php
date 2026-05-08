<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentoMagazzino extends Model
{
    protected $table = 'movimenti_magazzino';
    protected $primaryKey = 'IDMovimento';
    public $timestamps = false;

    protected $fillable = [
        'CodiceUnivoco_FK',
        'Quantita',
        'Tipo',
        'CostoTotale',
        'DataMovimento',
    ];

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'CodiceUnivoco_FK', 'CodiceUnivoco');
    }
}
