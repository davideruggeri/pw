<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dipendente;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin (Dipendente)
        $adminDip = Dipendente::firstOrCreate(
            ['Matricola' => 1001],
            [
                'Nome' => 'Admin',
                'Cognome' => 'Sistemista',
                'IDReparto_FK' => 5, // Amministrazione
                'IDRuolo_FK' => 14, // Contabile
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@azienda.it'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'matricola_fk' => $adminDip->Matricola
            ]
        );

        // 2. Venditore (Dipendente)
        $salesDip = Dipendente::firstOrCreate(
            ['Matricola' => 2001],
            [
                'Nome' => 'Mario',
                'Cognome' => 'Rossi',
                'IDReparto_FK' => 6, // Commerciale
                'IDRuolo_FK' => 15, // Addetto Vendite
            ]
        );

        User::firstOrCreate(
            ['email' => 'sales@azienda.it'],
            [
                'name' => 'Mario Rossi',
                'password' => Hash::make('password'),
                'role' => 'sales',
                'matricola_fk' => $salesDip->Matricola
            ]
        );

        // 3. Cliente
        $cliente = Cliente::firstOrCreate(
            ['CodiceCliente' => 'CLI001'],
            [
                'Nome' => 'Azienda Cliente SRL',
                'IndirizzoSpedizione' => 'Via Roma 1',
            ]
        );

        User::firstOrCreate(
            ['email' => 'cliente@test.it'],
            [
                'name' => 'Azienda Cliente SRL',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'codice_cliente_fk' => $cliente->CodiceCliente
            ]
        );
    }
}
