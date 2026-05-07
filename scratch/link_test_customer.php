<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\User;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'cliente@test.it')->first();
if ($user) {
    $user->codice_cliente_fk = 'C018';
    $user->save();
    echo "Utente cliente@test.it collegato a C018 (che ha ordini).\n";
} else {
    echo "Utente non trovato.\n";
}
