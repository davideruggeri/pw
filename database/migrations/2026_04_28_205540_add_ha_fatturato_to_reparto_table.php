<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reparto', function (Blueprint $table) {
            $table->boolean('ha_fatturato')->default(false)->after('NomeReparto');
        });

        // 1. Impostazione reparti con fatturato (Commerciale e Produzione)
        DB::table('reparto')->whereIn('IDReparto', [1, 6])->update(['ha_fatturato' => true]);

        // 2. Allineamento ruoli dipendenti per coerenza
        // ID 1 (Produzione) -> Ruolo 10 (Operatore Altoforno) o 16 (Manager Produzione)
        // Mantengo il ruolo se è già uno dei due, altrimenti imposto 10
        DB::table('dipendente')
            ->where('IDReparto_FK', 1)
            ->whereNotIn('IDRuolo_FK', [10, 16])
            ->update(['IDRuolo_FK' => 10]);

        // ID 2 (Manutenzione) -> Ruolo 11 (Tecnico Manutentore)
        DB::table('dipendente')->where('IDReparto_FK', 2)->update(['IDRuolo_FK' => 11]);

        // ID 3 (Controllo Qualità) -> Ruolo 12 (Analista di Laboratorio)
        DB::table('dipendente')->where('IDReparto_FK', 3)->update(['IDRuolo_FK' => 12]);

        // ID 4 (Logistica) -> Ruolo 13 (Addetto Logistica)
        DB::table('dipendente')->where('IDReparto_FK', 4)->update(['IDRuolo_FK' => 13]);

        // ID 5 (Amministrazione) -> Ruolo 14 (Contabile)
        DB::table('dipendente')->where('IDReparto_FK', 5)->update(['IDRuolo_FK' => 14]);

        // ID 6 (Commerciale) -> Ruolo 15 (Addetto Vendite)
        DB::table('dipendente')->where('IDReparto_FK', 6)->update(['IDRuolo_FK' => 15]);

        // 3. Riassegnazione ordini al reparto Commerciale
        // Prendiamo le matricole dei dipendenti del reparto Commerciale
        $salesAgents = DB::table('dipendente')
            ->where('IDReparto_FK', 6)
            ->pluck('Matricola')
            ->toArray();

        if (!empty($salesAgents)) {
            $orders = DB::table('ordine_vendita')->get();
            foreach ($orders as $order) {
                $randomAgent = $salesAgents[array_rand($salesAgents)];
                DB::table('ordine_vendita')
                    ->where('IDOrdineVendita', $order->IDOrdineVendita)
                    ->update(['Matricola_FK' => $randomAgent]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reparto', function (Blueprint $table) {
            $table->dropColumn('ha_fatturato');
        });
    }
};
