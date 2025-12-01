<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reflection;

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
            'user_id'       => User::inRandomOrder('id')->first()->id,
            'comment_id'=> Comment::inRandomOrder('id')->first()->id,
            'like'   => $this->faker->randomElement([1,0,null]),
        ];
    }
}
