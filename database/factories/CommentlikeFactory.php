<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Reflection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commentlike>
 */
class CommentlikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             // Lie chaque réflexion à un utilisateur existant aléatoirement
            'user_id'       => User::factory(),
            'reflection_id'=>Reflection::factory(),
            'created_at'    => ,
            'updated_at'    => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}
