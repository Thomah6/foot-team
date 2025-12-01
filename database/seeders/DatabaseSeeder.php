<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Commentlike;
use App\Models\Reflection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stat;
use App\Models\TeamStat;
use App\Models\Team;
use App\Models\Vote;
use App\Models\PlayerOfTheMonth;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Stat::factory(10)->create();
        Team::factory(5)->create();
        TeamStat::factory(15)->create();
        // Créer les utilisateurs de base d'abord

        // // Créer les utilisateurs de base d'abord
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // // Créer des utilisateurs supplémentaires pour les stats
        // User::factory()->create([
        //     'name' => 'Jean Dupont',
        //     'email' => 'jean@example.com',
        // ]);
        // Créer 5 utilisateurs fixes (admin, bureau, 3 joueurs) pour dev rapide
        // User::factory()->create([
        //     'name' => 'Admin Super',
        //     'email' => 'admin@test.com',
        //     'role' => 'admin',
        //     'password' => bcrypt('password'),
        // ]);

        User::factory()->create([
            'name' => 'Bureau Team',
            'email' => 'bureau@test.com',
            'role' => 'bureau',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@test.com',
            'role' => 'simple',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Alice Martin',
            'email' => 'alice.martin@test.com',
            'role' => 'simple',
            'password' => bcrypt('password'),
        ]);

        Reflection::factory(40)->create([]);
        Comment::factory(100)->create([]);
        Commentlike::factory(500)->create([]);

        // Images spécifiques pour les joueurs du mois (validées et fonctionnelles)
        // $playerImages = [
        //     'https://picsum.photos/seed/player1/400/500.jpg',
        //     'https://picsum.photos/seed/player2/400/500.jpg',
        //     'https://picsum.photos/seed/player3/400/500.jpg'
        // ];

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

        //Mois actuels pour éviter les erreurs
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

        // echo "\n Database seeded successfully !\n";
        Reflection::factory(10)->create();
        Vote::factory(10)->create();
        echo "\n Database seeded successfully !\n";
        // Reflection::factory(40)->create();
        // Comment::factory(200)->create();

        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Pierre Durand',
        //     'email' => 'pierre@example.com',
        // ]);

        // Ensuite créer les stats (maintenant il y a des utilisateurs)
        // $this->call([
        //     StatSeeder::class,
        // ]);
         $this->call([
            StatSeeder::class,
            PresenceSeeder::class,
        ]);
    }
}
