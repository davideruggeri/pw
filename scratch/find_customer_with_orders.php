<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\Cliente;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$cliente = Cliente::has('ordiniVendita')->with('user')->first();

if ($cliente && $cliente->user) {
    echo "Email: " . $cliente->user->email . "\n";
    echo "Nome: " . $cliente->user->name . "\n";
    echo "Ordini: " . $cliente->ordiniVendita()->count() . "\n";
} else {
    echo "Nessun cliente con ordini trovato.\n";
}
