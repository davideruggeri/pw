<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$prodManagers = DB::table('dipendente')
    ->where('IDReparto_FK', 1)
    ->where('IDRuolo_FK', 16)
    ->pluck('Matricola')
    ->toArray();

if (empty($prodManagers)) {
    echo "No production managers found.\n";
    exit;
}

$orders = DB::table('ordine_vendita')->get();
$count = 0;
foreach ($orders as $order) {
    // 30% degli ordini alla produzione
    if (rand(1, 100) <= 30) {
        DB::table('ordine_vendita')
            ->where('IDOrdineVendita', $order->IDOrdineVendita)
            ->update(['Matricola_FK' => $prodManagers[array_rand($prodManagers)]]);
        $count++;
    }
}

echo "Assigned $count orders to Production Managers.\n";
