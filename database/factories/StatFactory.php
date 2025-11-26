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
            'user_id' => User::factory(),
            'goals' => $this->faker->numberBetween(0, 5),
            'assists' => $this->faker->numberBetween(0, 3),
            'goals_saved' => $this->faker->numberBetween(0, 10),
            'goals_conceded' => $this->faker->numberBetween(0, 5),
            'is_goalkeeper' => $this->faker->boolean(20),
            'match_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'validated_by_admin' => $this->faker->boolean(80),
        ];
    }
}
