<?php

namespace App\Services;

use App\Repositories\Interfaces\OrdineRepositoryInterface;
use App\Repositories\Interfaces\ProdottoRepositoryInterface;
use App\Models\DettaglioVendita;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected $ordineRepo;
    protected $prodottoRepo;

    public function __construct(
        OrdineRepositoryInterface $ordineRepo,
        ProdottoRepositoryInterface $prodottoRepo
    ) {
        $this->ordineRepo = $ordineRepo;
        $this->prodottoRepo = $prodottoRepo;
    }

    /**
     * Crea un ordine e aggiorna il magazzino (Pattern Transaction)
     */
    public function createOrder(array $data, $matricola)
    {
        return DB::transaction(function () use ($data, $matricola) {
            // 1. Creazione testata ordine
            $ordine = $this->ordineRepo->create([
                'Data' => now()->format('Y-m-d'),
                'Stato' => 'Inviato',
                'CodiceCliente_FK' => $data['CodiceCliente_FK'],
                'Matricola_FK' => $matricola,
            ]);

            // 2. Processamento articoli
            foreach ($data['prodotti'] as $item) {
                $prodotto = $this->prodottoRepo->find($item['CodiceUnivoco']);

                // Validazione logica di business
                if ($prodotto->QuantitaGiacenza < $item['Quantita']) {
                    throw new Exception("Stock insufficiente per {$prodotto->NomeProdotto}");
                }

                // Creazione dettaglio (Logica di Factory interna)
                DettaglioVendita::create([
                    'IDOrdineVendita_FK' => $ordine->IDOrdineVendita,
                    'CodiceUnivoco_FK' => $prodotto->CodiceUnivoco,
                    'QuantitaRichiesta' => $item['Quantita'],
                    'PrezzoApplicato' => $prodotto->PrezzoUnitario,
                ]);

                // 3. Aggiornamento magazzino tramite Repository
                $this->prodottoRepo->updateStock($prodotto->CodiceUnivoco, $item['Quantita']);
            }

            return $ordine;
        });
    }
}
