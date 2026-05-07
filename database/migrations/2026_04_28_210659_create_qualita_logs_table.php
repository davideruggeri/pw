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
        Schema::create('qualita_log', function (Blueprint $table) {
            $table->id('IDLogQualita');
            $table->unsignedBigInteger('IDLogProduzione_FK');
            $table->integer('QuantitaScartata')->default(0);
            $table->string('Esito')->default('PASS'); // PASS / FAIL
            $table->text('NoteDifetto')->nullable();
            $table->timestamp('DataControllo')->useCurrent();

            $table->foreign('IDLogProduzione_FK')->references('IDLogProduzione')->on('produzione_log')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualita_log');
    }
};
