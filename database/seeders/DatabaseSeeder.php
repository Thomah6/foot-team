<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Reflection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stat;
use App\Models\TeamStat;
use App\Models\Team;
use App\Models\PlayerOfTheMonth;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Images spécifiques pour les joueurs du mois (validées et fonctionnelles)
        $playerImages = [
            'https://picsum.photos/seed/player1/400/500.jpg',
            'https://picsum.photos/seed/player2/400/500.jpg',
            'https://picsum.photos/seed/player3/400/500.jpg'
        ];

        // Ensuite créer les stats (maintenant il y a des utilisateurs)

        // -----------------------------------
        // 1) Création des utilisateurs
        // -----------------------------------
        $users = User::factory(10)->create();

        // -----------------------------------
        // 2) Création des stats pour chaque utilisateur
        // Stats sur plusieurs mois (réaliste)
        // -----------------------------------
        foreach ($users as $user) {
            Stat::factory()
                ->state([
                    'user_id' => $user->id,
                    'date' => fake()->dateTimeBetween('-6 months', 'now'),
                    'validated_by_admin' => fake()->boolean(80),
                ])
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
                ->state([
                    'user_id' => $user->id,
                    'date' => $month,
                    'validated_by_admin' => true,
                ])
                ->create();
        }

        echo "\n🌱 Database seeded successfully !\n";
    }
}
