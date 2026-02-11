<?php

namespace Database\Seeders;

use App\Models\Habitant;
use Illuminate\Database\Seeder;

class HabitantSeeder extends Seeder
{
    public function run(): void
    {
        // Quelques habitants fixes pour les tests manuels
        Habitant::create([
            'nom' => 'NDIAYE',
            'prenom' => 'Moussa',
            'email' => 'moussa.ndiaye@example.com',
            'telephone' => '771234567',
            'date_naissance' => '1985-03-10',
            'quartier' => 'Plateau',
        ]);

        Habitant::create([
            'nom' => 'DIOP',
            'prenom' => 'Awa',
            'email' => 'awa.diop@example.com',
            'telephone' => '781112233',
            'date_naissance' => '1990-07-22',
            'quartier' => 'Mermoz',
        ]);

        Habitant::create([
            'nom' => 'FALL',
            'prenom' => 'Cheikh',
            'email' => 'cheikh.fall@example.com',
            'telephone' => '761234890',
            'date_naissance' => '1978-11-05',
            'quartier' => 'Guédiawaye',
        ]);

        // Et quelques habitants générés aléatoirement
        Habitant::factory(7)->create();
    }
}

