<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Reflection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        // Créer les utilisateurs de base d'abord
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Créer des utilisateurs supplémentaires pour les stats
        User::factory()->create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
        ]);

        User::factory()->create([
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
        ]);

        // Images spécifiques pour les joueurs du mois (validées et fonctionnelles)
        $playerImages = [
            'https://picsum.photos/seed/player1/400/500.jpg',
            'https://picsum.photos/seed/player2/400/500.jpg', 
            'https://picsum.photos/seed/player3/400/500.jpg'
        ];

        // Mois actuels pour éviter les erreurs
        $months = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonths(2)->startOfMonth(),
        ];

        // foreach ($months as $index => $month) {
        //     $user = $users->random();

        //     // Crée un "Joueur du Mois" avec une image spécifique
        //     PlayerOfTheMonth::factory()->create([
        //         'user_id' => $user->id,
        //         'month'   => $month,
        //         'is_active' => $index === 0, // Le premier est actif
        //         'image'   => $playerImages[$index], // Image spécifique pour chaque mois
        //     ]);

        //     // Génère des stats validées liées à ce mois
        //     Stat::factory()
        //         ->count(rand(3, 8))
        //         ->for($user)
        //         ->state([
        //             'date' => $month,
        //             'validated_by_admin' => true,
        //         ])
        //         ->create();
        // }

        echo "\n Database seeded successfully !\n";
        // Reflection::factory(40)->create();
        // Comment::factory(200)->create();

        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Pierre Durand',
            'email' => 'pierre@example.com',
        ]);

        // Ensuite créer les stats (maintenant il y a des utilisateurs)
        $this->call([
            StatSeeder::class,
        ]);
    }
}
