<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table      = 'categoria';
    protected $primaryKey = 'IDCategoria';
    public    $incrementing = true;        // INT auto-increment
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = ['NomeCategoria', 'Descrizione'];

    // ── Relazioni ─────────────────────────────────────────────
    public function prodotti()
    {
        return $this->hasMany(Prodotto::class, 'IDCategoria_FK', 'IDCategoria');
    }
}
