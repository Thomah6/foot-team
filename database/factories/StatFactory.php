<?php

namespace Database\Factories;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stat>
 */
class StatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'            => User::factory(),
            'goals'              => $this->faker->numberBetween(0, 20),
            'assists'            => $this->faker->numberBetween(0, 15),
            'goals_against'      => $this->faker->numberBetween(0, 10),
            'matches_played'     => $this->faker->numberBetween(0, 30),
            'date'               => $this->faker->date(),
            'validated_by_admin' => $this->faker->boolean(30), // 30% true
        ];
    }
}
