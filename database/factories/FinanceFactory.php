<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance>
 */
class FinanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'montant' => $this->faker->randomFloat(2, 5, 200),
            'type' => $this->faker->randomElement(['cotisation', 'dépense']),
            'statut_valide' => $this->faker->boolean(70),
            'description' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
