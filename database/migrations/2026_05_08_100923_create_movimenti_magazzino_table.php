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
        Schema::create('movimenti_magazzino', function (Blueprint $table) {
            $table->id('IDMovimento');
            $table->string('CodiceUnivoco_FK', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci');
            $table->decimal('Quantita', 10, 2);
            $table->enum('Tipo', ['carico', 'scarico']);
            $table->decimal('CostoTotale', 12, 2)->default(0);
            $table->timestamp('DataMovimento')->useCurrent();
            
            $table->foreign('CodiceUnivoco_FK')->references('CodiceUnivoco')->on('prodotto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimenti_magazzino');
    }
};
