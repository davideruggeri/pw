<?php

use App\Models\ManutenzioneLog;
use App\Models\Dipendente;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Pulizia eventuale
DB::table('manutenzione_log')->truncate();

$tecnici = Dipendente::where('IDReparto_FK', 2)->get();

if ($tecnici->isEmpty()) {
    echo "Nessun tecnico manutentore trovato.\n";
    exit;
}

// Generiamo 10 interventi nell'ultimo mese
for ($i = 0; $i < 10; $i++) {
    $data = now()->subDays(rand(0, 30));
    $tipo = (rand(1, 10) <= 7) ? 'Programmata' : 'Straordinaria';
    $ore = ($tipo == 'Programmata') ? rand(2, 6) : rand(8, 24);
    $costo = ($tipo == 'Programmata') ? rand(100, 500) : rand(1000, 5000);
    
    ManutenzioneLog::create([
        'TipoIntervento' => $tipo,
        'OreFermoMacchina' => $ore,
        'CostoRicambi' => $costo,
        'DataIntervento' => $data->copy()->setHour(rand(8, 18)),
        'Matricola_FK' => $tecnici->random()->Matricola
    ]);
}

echo "Generati 10 interventi di manutenzione.\n";
