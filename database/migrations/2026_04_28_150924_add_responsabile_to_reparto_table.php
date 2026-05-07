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
            $table->integer('IDResponsabile_FK')->nullable()->after('NomeReparto');
            $table->foreign('IDResponsabile_FK')->references('Matricola')->on('dipendente')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reparto', function (Blueprint $table) {
            $table->dropForeign(['IDResponsabile_FK']);
            $table->dropColumn('IDResponsabile_FK');
        });
    }
};
