<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Inventory ──────────────────────────────────────────────
    Route::prefix('inventory')->name('inventory.')->group(function () {

        // GET /api/v1/inventory/sotto-scorta
        Route::get('sotto-scorta', [InventoryController::class, 'prodottiSottoScorta'])
            ->name('sotto-scorta');

        // GET /api/v1/inventory/fatturato-per-cliente
        Route::get('fatturato-per-cliente', [InventoryController::class, 'fatturatoPerCliente'])
            ->name('fatturato-per-cliente');
    });
});
