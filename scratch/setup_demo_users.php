<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Dipendente;
use Illuminate\Support\Facades\Hash;

$roles = [
    1 => ['email' => 'produzione@azienda.it', 'name' => 'Operatore Produzione'],
    2 => ['email' => 'manutenzione@azienda.it', 'name' => 'Tecnico Manutenzione'],
    3 => ['email' => 'qualita@azienda.it', 'name' => 'Addetto Qualità'],
    4 => ['email' => 'logistica@azienda.it', 'name' => 'Operatore Logistica'],
];

foreach ($roles as $repartoId => $data) {
    echo "Configurazione {$data['email']}...\n";
    
    // 1. Crea o trova il Dipendente "Demo" per questo reparto
    $matricolaDemo = 9000 + $repartoId;
    $dipendente = Dipendente::updateOrCreate(
        ['Matricola' => $matricolaDemo],
        [
            'Nome' => 'Demo',
            'Cognome' => str_replace('Operatore ', '', $data['name']),
            'IDReparto_FK' => $repartoId,
            'DataAssunzione' => now(),
            'Stato' => 'Attivo'
        ]
    );

    // 2. Crea o trova l'utente
    User::updateOrCreate(
        ['email' => $data['email']],
        [
            'name' => $data['name'],
            'password' => Hash::make('password'),
            'role' => ($repartoId <= 3) ? 'production' : 'logistics', // Mantiene la logica dei ruoli base
            'matricola_fk' => $matricolaDemo,
            'password_changed' => 1
        ]
    );
}

echo "Configurazione completata.\n";
