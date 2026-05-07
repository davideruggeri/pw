<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\DipendenteRepositoryInterface;
use App\Repositories\Eloquent\DipendenteRepository;
use App\Repositories\Interfaces\ProdottoRepositoryInterface;
use App\Repositories\Eloquent\ProdottoRepository;
use App\Repositories\Interfaces\OrdineRepositoryInterface;
use App\Repositories\Eloquent\OrdineRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DipendenteRepositoryInterface::class, DipendenteRepository::class);
        $this->app->bind(ProdottoRepositoryInterface::class, ProdottoRepository::class);
        $this->app->bind(OrdineRepositoryInterface::class, OrdineRepository::class);
    }

    public function boot()
    {
        //
    }
}
