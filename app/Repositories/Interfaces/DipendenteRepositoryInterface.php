<?php

namespace App\Repositories\Interfaces;

interface DipendenteRepositoryInterface
{
    public function all();
    public function find($matricola);
    public function create(array $data);
    public function update($matricola, array $data);
    public function delete($matricola);
}
