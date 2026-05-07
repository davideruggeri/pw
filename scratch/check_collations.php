<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

foreach(['prodotto', 'preferiti', 'cliente', 'users'] as $table) {
    echo "Table $table:\n";
    foreach(DB::select("SHOW FULL COLUMNS FROM $table") as $c) {
        if ($c->Collation) echo "  {$c->Field}: {$c->Collation}\n";
    }
}
