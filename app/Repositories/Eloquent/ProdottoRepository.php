<?php

namespace App\Repositories\Eloquent;

use App\Models\Prodotto;
use App\Repositories\Interfaces\ProdottoRepositoryInterface;

class ProdottoRepository implements ProdottoRepositoryInterface
{
    public function all()
    {
        return Prodotto::orderBy('Descrizione')->get();
    }

    public function find($codice)
    {
        return Prodotto::findOrFail($codice);
    }

    public function getLowStock()
    {
        return Prodotto::whereColumn('QuantitaGiacenza', '<', 'ScortaMinima')->get();
    }

    public function updateStock($codice, $quantity)
    {
        $prodotto = $this->find($codice);
        $prodotto->QuantitaGiacenza -= $quantity;
        $prodotto->save();
        return $prodotto;
    }
}
