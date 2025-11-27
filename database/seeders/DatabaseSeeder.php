<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Stat;
use App\Models\PlayerOfTheMonth;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // -----------------------------------
        // 1) Création des utilisateurs
        // -----------------------------------
        $users = User::factory(20)->create();

        // -----------------------------------
        // 2) Création des stats pour chaque utilisateur
        // Stats sur plusieurs mois (réaliste)
        // -----------------------------------
        foreach ($users as $user) {
            Stat::factory()
                ->count(rand(10, 20))
                ->state(function () {
                    return [
                        'date' => fake()->dateTimeBetween('-6 months', 'now'),
                        'validated_by_admin' => fake()->boolean(80),
                    ];
                })
                ->for($user)
                ->create();
        }

        // -----------------------------------
        // 3) Création de 3 Joueurs du Mois
        // Dont un actif
        // -----------------------------------

        // Mois actuels pour éviter les erreurs
        $months = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonths(2)->startOfMonth(),
        ];

        foreach ($months as $index => $month) {
            $user = $users->random();

            // Crée un "Joueur du Mois"
            PlayerOfTheMonth::factory()->create([
                'user_id' => $user->id,
                'month'   => $month,
                'is_active' => $index === 0, // Le premier est actif
            ]);

            // Génère des stats validées liées à ce mois
            Stat::factory()
                ->count(rand(3, 8))
                ->for($user)
                ->state([
                    'date' => $month,
                    'validated_by_admin' => true,
                ])
                ->create();
        }

        echo "\n🌱 Database seeded successfully !\n";
    }
}
