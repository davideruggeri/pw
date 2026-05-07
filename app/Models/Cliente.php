<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table      = 'cliente';
    protected $primaryKey = 'CodiceCliente';
    public    $incrementing = false;       // PK stringa
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'CodiceCliente',
        'Nome',
        'IndirizzoSpedizione',
    ];

    // ── Relazioni ─────────────────────────────────────────────
    public function ordiniVendita()
    {
        return $this->hasMany(OrdineVendita::class, 'CodiceCliente_FK', 'CodiceCliente');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'codice_cliente_fk', 'CodiceCliente');
    }

    public function preferiti()
    {
        return $this->belongsToMany(Prodotto::class, 'preferiti', 'CodiceCliente_FK', 'CodiceUnivoco_FK')
                    ->withTimestamps();
    }
}
