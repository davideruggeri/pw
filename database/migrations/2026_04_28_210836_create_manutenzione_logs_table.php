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
        Schema::create('manutenzione_log', function (Blueprint $table) {
            $table->id('IDLogManutenzione');
            $table->string('TipoIntervento'); // Programmata / Straordinaria
            $table->integer('OreFermoMacchina');
            $table->decimal('CostoRicambi', 10, 2)->default(0);
            $table->timestamp('DataIntervento')->useCurrent();
            $table->integer('Matricola_FK'); // Tecnico manutentore

            $table->foreign('Matricola_FK')->references('Matricola')->on('dipendente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzione_log');
    }
};
