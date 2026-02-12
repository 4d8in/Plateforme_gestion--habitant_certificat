<?php

namespace Database\Factories;

use App\Models\Habitant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Habitant>
 */
class HabitantFactory extends Factory
{
    protected $model = Habitant::class;

    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'nom' => strtoupper($faker->lastName()),
            'prenom' => $faker->firstName(),
            'email' => $faker->unique()->safeEmail(),
            'telephone' => $faker->numerify('77#######'),
            'date_naissance' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'quartier' => $faker->randomElement(['Plateau', 'Mermoz', 'Guédiawaye', 'Pikine', 'Parcelles']),
            'password' => bcrypt('secret123'),
        ];
    }
}
