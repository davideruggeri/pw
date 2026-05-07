<?php

namespace App\Repositories\Eloquent;

use App\Models\OrdineVendita;
use App\Repositories\Interfaces\OrdineRepositoryInterface;

class OrdineRepository implements OrdineRepositoryInterface
{
    public function create(array $data)
    {
        return OrdineVendita::create($data);
    }

    public function getRecent($limit = 5)
    {
        return OrdineVendita::with(['cliente', 'dettagliVendita'])
            ->orderByDesc('Data')
            ->limit($limit)
            ->get();
    }

    public function getActiveCount()
    {
        return OrdineVendita::where('Stato', '!=', 'Completato')->count();
    }
}
