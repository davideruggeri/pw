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
        Schema::create('produzione_log', function (Blueprint $table) {
            $table->id('IDLogProduzione');
            $table->string('CodiceUnivoco_FK', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci');
            $table->integer('QuantitaProdotta');
            $table->integer('Matricola_FK');
            $table->decimal('CostoEnergiaStimato', 10, 2)->default(0);
            $table->timestamp('DataProduzione')->useCurrent();
            
            $table->foreign('CodiceUnivoco_FK')->references('CodiceUnivoco')->on('prodotto');
            $table->foreign('Matricola_FK')->references('Matricola')->on('dipendente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produzione_log');
    }
};
