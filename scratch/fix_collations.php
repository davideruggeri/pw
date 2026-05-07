<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

try {
    echo "Inizio allineamento collation...\n";

    // 1. Allinea tabella preferiti
    DB::statement("ALTER TABLE preferiti CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
    echo "- Tabella 'preferiti' convertita.\n";

    // 2. Allinea colonna codice_cliente_fk in users
    DB::statement("ALTER TABLE users MODIFY codice_cliente_fk VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
    echo "- Colonna 'users.codice_cliente_fk' convertita.\n";

    echo "Allineamento completato con successo!\n";
} catch (\Exception $e) {
    echo "ERRORE: " . $e->getMessage() . "\n";
}
