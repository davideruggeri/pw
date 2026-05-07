<?php

namespace App\Repositories\Interfaces;

interface ProdottoRepositoryInterface
{
    public function all();
    public function find($codice);
    public function getLowStock();
    public function updateStock($codice, $quantity);
}
