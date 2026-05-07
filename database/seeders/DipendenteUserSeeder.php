<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DipendenteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dipendenti = \App\Models\Dipendente::all();
        $passwordStandard = \Illuminate\Support\Facades\Hash::make('Benvenuto2026!');

        foreach ($dipendenti as $dip) {
            // Genera email: i.cognome@azienda.it
            $iniziale = strtolower(substr($dip->Nome, 0, 1));
            $cognome = strtolower(str_replace(' ', '', $dip->Cognome));
            $email = "{$iniziale}.{$cognome}@azienda.it";

            // Mapping Ruoli
            $role = match ($dip->IDRuolo_FK) {
                13      => 'logistics',
                15      => 'sales',
                16      => 'admin',
                10, 11, 12 => 'production',
                default => 'production',
            };

            \App\Models\User::updateOrCreate(
                ['email' => $email],
                [
                    'name'             => "{$dip->Nome} {$dip->Cognome}",
                    'password'         => $passwordStandard,
                    'role'             => $role,
                    'matricola_fk'     => $dip->Matricola,
                    'password_changed' => false,
                ]
            );
        }
    }
}
