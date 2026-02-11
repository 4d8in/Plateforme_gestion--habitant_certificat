<?php

namespace Database\Seeders;

use App\Models\Certificat;
use App\Models\Habitant;
use Illuminate\Database\Seeder;

class CertificatSeeder extends Seeder
{
    public function run(): void
    {
        // On s'assure d'avoir des habitants
        if (Habitant::count() === 0) {
            $this->call(HabitantSeeder::class);
        }

        $habitants = Habitant::all();

        // Pour chaque habitant, on crée quelques certificats avec des statuts variés
        foreach ($habitants as $habitant) {
            // en_attente
            Certificat::create([
                'habitant_id' => $habitant->id,
                'date_certificat' => now()->subDays(rand(1, 30))->toDateString(),
                'statut' => Certificat::STATUT_EN_ATTENTE,
                'montant' => 5000,
                'reference_paiement' => 'TEST_ATT_'.$habitant->id,
            ]);

            // paye
            Certificat::create([
                'habitant_id' => $habitant->id,
                'date_certificat' => now()->subDays(rand(31, 90))->toDateString(),
                'statut' => Certificat::STATUT_PAYE,
                'montant' => 5000,
                'reference_paiement' => 'TEST_PAYE_'.$habitant->id,
            ]);

            // delivre
            Certificat::create([
                'habitant_id' => $habitant->id,
                'date_certificat' => now()->subDays(rand(91, 180))->toDateString(),
                'statut' => Certificat::STATUT_DELIVRE,
                'montant' => 5000,
                'reference_paiement' => 'TEST_DEL_'.$habitant->id,
            ]);
        }
    }
}

