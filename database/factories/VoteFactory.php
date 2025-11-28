<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reflection_id' => \App\Models\Reflection::inRandomOrder('id')->first()->id,
            'user_id' => \App\Models\User::inRandomOrder('id')->first()->id,
            'value' => $this->faker->randomElement([1, -1]),
        ];
    }
}
