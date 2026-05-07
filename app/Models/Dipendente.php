<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dipendente extends Model
{
    protected $table      = 'dipendente';
    protected $primaryKey = 'Matricola';
    public    $incrementing = true;        // INT, assumiamo auto-increment
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = [
        'Matricola',
        'Nome',
        'Cognome',
        'IDReparto_FK',
        'IDRuolo_FK',
    ];

    public function reparto()
    {
        return $this->belongsTo(Reparto::class, 'IDReparto_FK', 'IDReparto');
    }

    public function ruolo()
    {
        return $this->belongsTo(Ruolo::class, 'IDRuolo_FK', 'IDRuolo');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'matricola_fk', 'Matricola');
    }
}
