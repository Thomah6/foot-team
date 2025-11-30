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
        User::factory(10)->create();
        // Stat::factory(10)->create();
        // Team::factory(5)->create();
        // TeamStat::factory(15)->create();
    
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
