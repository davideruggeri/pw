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
        Schema::table('prodotto', function (Blueprint $table) {
            $table->decimal('CostoProduzione', 10, 2)->after('PrezzoVendita')->default(0);
        });

        // Inizializziamo il costo di produzione come il 65% del prezzo di vendita
        DB::table('prodotto')->update([
            'CostoProduzione' => DB::raw('PrezzoVendita * 0.65')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodotto', function (Blueprint $table) {
            $table->dropColumn('CostoProduzione');
        });
    }
};
