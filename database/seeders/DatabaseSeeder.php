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
        // Créer 5 utilisateurs fixes (admin, bureau, 3 joueurs) pour dev rapide
        User::factory()->create([
            'name' => 'Admin Super',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

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

        User::factory()->create([
            'name' => 'Bob Leroy',
            'email' => 'bob.leroy@test.com',
            'role' => 'simple',
            'password' => bcrypt('password'),
        ]);

        // Créer quelques utilisateurs factices supplémentaires
        User::factory(8)->create();
    
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

        // Appeler les seeders dépendants
        $this->call([
            StatSeeder::class,
            PresenceSeeder::class,
        ]);
    }
}
