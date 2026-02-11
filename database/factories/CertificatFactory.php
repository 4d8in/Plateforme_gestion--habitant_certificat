<?php

namespace Database\Factories;

use App\Models\Certificat;
use App\Models\Habitant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certificat>
 */
class CertificatFactory extends Factory
{
    protected $model = Certificat::class;

    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'habitant_id' => Habitant::factory(),
            'date_certificat' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'statut' => $faker->randomElement([
                Certificat::STATUT_EN_ATTENTE,
                Certificat::STATUT_PAYE,
                Certificat::STATUT_DELIVRE,
            ]),
            'montant' => (int) config('certificat.default_montant', 5000),
            'reference_paiement' => 'TEST_'.strtoupper($faker->bothify('########')),
        ];
    }
}
