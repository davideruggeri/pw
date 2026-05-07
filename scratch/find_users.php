<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reparto;
use App\Models\Dipendente;

foreach(Reparto::all() as $r) {
    $d = Dipendente::where('IDReparto_FK', $r->IDReparto)->first();
    if($d && $d->user) {
        echo "Reparto {$r->IDReparto} ({$r->NomeReparto}): {$d->user->email}\n";
    }
}

// Add customer
$c = App\Models\User::where('role', 'customer')->first();
if($c) echo "Cliente: {$c->email}\n";
