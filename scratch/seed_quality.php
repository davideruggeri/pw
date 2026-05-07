<?php

use App\Models\ProduzioneLog;
use App\Models\QualitaLog;
use App\Models\Prodotto;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Pulizia eventuale
DB::table('qualita_log')->truncate();

$productionLogs = ProduzioneLog::all();

if ($productionLogs->isEmpty()) {
    echo "Nessun log di produzione trovato.\n";
    exit;
}

$scarti = 0;
foreach ($productionLogs as $log) {
    // Ogni lotto ha un controllo qualità
    // Il 95% passa, il 5% ha degli scarti
    $fail = rand(1, 100) <= 5;
    $quantitaScartata = 0;
    $esito = 'PASS';
    $note = null;

    if ($fail) {
        $quantitaScartata = rand(5, floor($log->QuantitaProdotta * 0.1)); // Scartiamo fino al 10% del lotto
        $esito = 'FAIL';
        $note = "Rilevate impurità nel materiale / Difetto fusione";
    }

    QualitaLog::create([
        'IDLogProduzione_FK' => $log->IDLogProduzione,
        'QuantitaScartata' => $quantitaScartata,
        'Esito' => $esito,
        'NoteDifetto' => $note,
        'DataControllo' => $log->DataProduzione // Controllo fatto lo stesso giorno
    ]);

    if ($quantitaScartata > 0) {
        // Sottraiamo la quantità scartata dalla giacenza reale
        $prodotto = Prodotto::find($log->CodiceUnivoco_FK);
        if ($prodotto) {
            $prodotto->decrement('QuantitaGiacenza', $quantitaScartata);
        }
        $scarti += $quantitaScartata;
    }
}

echo "Generati controlli qualità. Totale scarti: $scarti kg.\n";
