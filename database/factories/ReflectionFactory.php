<?php

namespace Database\Factories;

use App\Models\User; // Assurez-vous d'importer le modèle User
use Illuminate\Database\Eloquent\Factories\Factory;

class ReflectionFactory extends Factory
{
    /**
     * Le nom du modèle correspondant au factory.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Lie chaque réflexion à un utilisateur existant aléatoirement
            'user_id'       => User::inRandomOrder('id')->first()->id,
            'titre'         => $this->faker->sentence(4),
            'contenu'       => $this->faker->realText(400),
            'statut'        => $this->faker->randomElement(['ouvert', 'ferme', 'valide']),
            'date_fin_vote' => $this->faker->optional(0.7)->dateTimeInInterval('+1 week', '+2 weeks'), // 70% de chance d'avoir une date
            'created_at'    => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at'    => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * État de la réflexion désactivée.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'ferme',
            ];
        });
    }

    /**
     * État de la réflexion très courte (pour les tests de validation).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function short()
    {
        return $this->state(function (array $attributes) {
            return [
                'contenu' => $this->faker->sentence(3),
            ];
        });
    }
}
