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
        Schema::create('preferiti', function (Blueprint $table) {
            $table->id();
            $table->string('CodiceCliente_FK');
            $table->string('CodiceUnivoco_FK');
            
            $table->foreign('CodiceCliente_FK')->references('CodiceCliente')->on('cliente')->onDelete('cascade');
            $table->foreign('CodiceUnivoco_FK')->references('CodiceUnivoco')->on('prodotto')->onDelete('cascade');
            
            $table->unique(['CodiceCliente_FK', 'CodiceUnivoco_FK']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferiti');
    }
};
