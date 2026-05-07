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
        Schema::table('ruolo', function (Blueprint $table) {
            $table->integer('Livello')->default(1)->after('NomeRuolo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruolo', function (Blueprint $table) {
            $table->dropColumn('Livello');
        });
    }
};
