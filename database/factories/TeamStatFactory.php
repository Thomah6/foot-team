<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamStat>
 */
class TeamStatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => \App\Models\Team::factory(),
            'wins' => $this->faker->numberBetween(0, 10),
            'losses' => $this->faker->numberBetween(0, 10),
            'draws' => $this->faker->numberBetween(0, 10),
            'month' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-01'),
            'validated' => $this->faker->boolean(80), // 80% chance of being true
        ];
    }
}
