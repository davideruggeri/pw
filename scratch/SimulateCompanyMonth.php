<?php

use App\Models\ProduzioneLog;
use App\Models\QualitaLog;
use App\Models\ManutenzioneLog;
use App\Models\OrdineVendita;
use App\Models\DettaglioVendita;
use App\Models\Prodotto;
use App\Models\Dipendente;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- AVVIO SIMULAZIONE AZIENDALE (3 ANNI) ---\n";

// 1. Cleanup Totale Log
DB::table('qualita_log')->delete();
DB::table('manutenzione_log')->delete();
DB::table('produzione_log')->delete();
DB::table('dettaglio_vendita')->delete();
DB::table('ordine_vendita')->delete();

// Reset Giacenze a zero per iniziare puliti
DB::table('prodotto')->update(['QuantitaGiacenza' => 0]);

// 2. Recupero Entità
$prodotti = Prodotto::all();
$clienti = Cliente::all();
$managersProd = Dipendente::where('IDReparto_FK', 1)->where('IDRuolo_FK', 16)->pluck('Matricola')->toArray();
$tecnici = Dipendente::where('IDReparto_FK', 2)->pluck('Matricola')->toArray();
$venditori = Dipendente::where('IDReparto_FK', 6)->pluck('Matricola')->toArray();

if (empty($managersProd) || empty($tecnici) || empty($venditori)) {
    echo "Errore: Personale non configurato correttamente nei reparti.\n";
    exit;
}

$nextOrderId = 1;

// 3. Ciclo di Simulazione (da 3 anni fa a oggi = 1095 giorni)
for ($i = 1095; $i >= 0; $i--) {
    $currentDate = now()->subDays($i);
    if ($i % 100 == 0) echo "Simulazione Giorni Rimanenti: " . $i . "\n";

    // --- A. MANUTENZIONE (Possibile Downtime) ---
    $downtimeHours = 0;
    if (rand(1, 100) <= 5) { // 5% di probabilità di guasto o manutenzione giornaliera
        $tipo = (rand(1, 10) <= 7) ? 'Programmata' : 'Straordinaria';
        $downtimeHours = ($tipo == 'Programmata') ? rand(2, 4) : rand(5, 12);
        
        ManutenzioneLog::create([
            'TipoIntervento' => $tipo,
            'OreFermoMacchina' => $downtimeHours,
            'CostoRicambi' => ($tipo == 'Programmata') ? rand(100, 300) : rand(800, 3000),
            'DataIntervento' => $currentDate->copy()->setHour(rand(8, 12)),
            'Matricola_FK' => $tecnici[array_rand($tecnici)]
        ]);
    }

    // --- B. PRODUZIONE (Influenzata dal Downtime) ---
    // Capacità massima giornaliera teorica: 2000 kg totali
    $productionCapacity = floor(2000 * (1 - ($downtimeHours / 24)));
    
    if ($productionCapacity > 200) {
        $numBatches = rand(2, 4);
        for ($b = 0; $b < $numBatches; $b++) {
            $prodotto = $prodotti->random();
            $qty = floor($productionCapacity / $numBatches);
            
            $prodLog = ProduzioneLog::create([
                'CodiceUnivoco_FK' => $prodotto->CodiceUnivoco,
                'QuantitaProdotta' => $qty,
                'Matricola_FK' => $managersProd[array_rand($managersProd)],
                'CostoEnergiaStimato' => $qty * 0.12,
                'DataProduzione' => $currentDate->copy()->setHour(rand(8, 20))
            ]);

            // --- C. QUALITÀ (Controllo immediato del lotto) ---
            $fail = rand(1, 100) <= 6; // 6% tasso di difettosità
            $scarto = $fail ? floor($qty * (rand(5, 15) / 100)) : 0;
            
            QualitaLog::create([
                'IDLogProduzione_FK' => $prodLog->IDLogProduzione,
                'QuantitaScartata' => $scarto,
                'Esito' => ($scarto > 0) ? 'FAIL' : 'PASS',
                'NoteDifetto' => ($scarto > 0) ? "Inclusione scoria / Temperatura instabile" : null,
                'DataControllo' => $prodLog->DataProduzione
            ]);

            // Aggiornamento Magazzino (Solo il netto buono)
            $qtyNetta = $qty - $scarto;
            DB::table('prodotto')->where('CodiceUnivoco', $prodotto->CodiceUnivoco)->increment('QuantitaGiacenza', $qtyNetta);
        }
    }

    // --- D. VENDITE (Commerciale) ---
    // Generiamo ordini solo per prodotti che hanno giacenza
    $prodottiDisponibili = Prodotto::where('QuantitaGiacenza', '>', 10)->get();
    if ($prodottiDisponibili->isNotEmpty()) {
        $numOrders = rand(1, 5);
        for ($o = 0; $o < $numOrders; $o++) {
            $cliente = $clienti->random();
            $venditore = $venditori[array_rand($venditori)];
            
            $ordineId = $nextOrderId++;
            DB::table('ordine_vendita')->insert([
                'IDOrdineVendita' => $ordineId,
                'Data' => $currentDate->copy()->setHour(rand(9, 18)),
                'Stato' => 'Completato',
                'CodiceCliente_FK' => $cliente->CodiceCliente,
                'Matricola_FK' => $venditore
            ]);

            // 1-3 prodotti per ordine
            $items = $prodottiDisponibili->random(rand(1, min(3, $prodottiDisponibili->count())));
            foreach ($items as $item) {
                // Per evitare problemi di constraint, controlliamo che la giacenza sia ancora sufficiente nel loop
                $giacenzaAttuale = DB::table('prodotto')->where('CodiceUnivoco', $item->CodiceUnivoco)->value('QuantitaGiacenza');
                if ($giacenzaAttuale > 5) {
                    $qtyToSell = rand(5, min(50, $giacenzaAttuale));
                    
                    DB::table('dettaglio_vendita')->insert([
                        'IDOrdineVendita_FK' => $ordineId,
                        'CodiceUnivoco_FK' => $item->CodiceUnivoco,
                        'QuantitaRichiesta' => $qtyToSell,
                        'PrezzoApplicato' => $item->PrezzoVendita * (rand(95, 105) / 100) // oscillazione prezzo
                    ]);

                    // Decremento Magazzino
                    DB::table('prodotto')->where('CodiceUnivoco', $item->CodiceUnivoco)->decrement('QuantitaGiacenza', $qtyToSell);
                }
            }
        }
    }
}

echo "--- SIMULAZIONE COMPLETATA CON SUCCESSO ---\n";
echo "L'azienda ha ora uno storico coerente di 3 anni.\n";
