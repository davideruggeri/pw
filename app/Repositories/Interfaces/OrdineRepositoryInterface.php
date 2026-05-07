<?php

namespace App\Repositories\Interfaces;

interface OrdineRepositoryInterface
{
    public function create(array $data);
    public function getRecent($limit = 5);
    public function getActiveCount();
}
