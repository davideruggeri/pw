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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'sales', 'logistics', 'production', 'customer'])->default('customer')->after('password');
            $table->integer('matricola_fk')->nullable()->after('role');
            $table->string('codice_cliente_fk', 50)->nullable()->after('matricola_fk');
            
            // Indici per performance
            $table->index('matricola_fk');
            $table->index('codice_cliente_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'matricola_fk', 'codice_cliente_fk']);
        });
    }
};
