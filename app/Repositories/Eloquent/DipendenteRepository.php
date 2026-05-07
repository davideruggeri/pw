<?php

namespace App\Repositories\Eloquent;

use App\Models\Dipendente;
use App\Repositories\Interfaces\DipendenteRepositoryInterface;

class DipendenteRepository implements DipendenteRepositoryInterface
{
    public function all()
    {
        return Dipendente::with(['reparto', 'ruolo'])->orderBy('Cognome')->get();
    }

    public function find($matricola)
    {
        return Dipendente::findOrFail($matricola);
    }

    public function create(array $data)
    {
        return Dipendente::create($data);
    }

    public function update($matricola, array $data)
    {
        $employee = $this->find($matricola);
        $employee->update($data);
        return $employee;
    }

    public function delete($matricola)
    {
        $employee = $this->find($matricola);
        return $employee->delete();
    }
}
