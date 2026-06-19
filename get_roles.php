<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

foreach (App\Models\Ruolo::all() as $ruolo) {
    echo "ID: {$ruolo->IDRuolo} - Nome: {$ruolo->NomeRuolo} - Livello: {$ruolo->Livello}\n";
}
