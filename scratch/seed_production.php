<?php

use App\Models\ProduzioneLog;
use App\Models\Prodotto;
use App\Models\Dipendente;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Pulizia eventuale
DB::table('produzione_log')->truncate();

$prodotti = Prodotto::all();
$managers = Dipendente::where('IDReparto_FK', 1)->where('IDRuolo_FK', 16)->get();

if ($prodotti->isEmpty() || $managers->isEmpty()) {
    echo "Dati insufficienti (prodotti o manager) per generare il log.\n";
    exit;
}

for ($i = 30; $i >= 0; $i--) {
    $data = now()->subDays($i);
    
    // Generiamo 2-5 log al giorno
    $numLogs = rand(2, 5);
    
    for ($j = 0; $j < $numLogs; $j++) {
        $prodotto = $prodotti->random();
        $manager = $managers->random();
        $quantita = rand(50, 500);
        $costoEnergia = $quantita * 0.15; // 0.15€ per unità prodotta
        
        ProduzioneLog::create([
            'CodiceUnivoco_FK' => $prodotto->CodiceUnivoco,
            'QuantitaProdotta' => $quantita,
            'Matricola_FK' => $manager->Matricola,
            'CostoEnergiaStimato' => $costoEnergia,
            'DataProduzione' => $data->copy()->setHour(rand(8, 18))->setMinute(rand(0, 59))
        ]);
        
        // Aggiorniamo la giacenza
        $prodotto->increment('QuantitaGiacenza', $quantita);
    }
}

echo "Generati log di produzione per gli ultimi 30 giorni.\n";
