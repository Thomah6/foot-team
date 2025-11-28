<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Stat;
use Illuminate\Support\Facades\DB;

class StatSeeder extends Seeder
{
    public function run()
    {
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vider la table stats
        Stat::truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Récupérer tous les utilisateurs
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Veuillez d\'abord exécuter UserSeeder.');
            return;
        }

        // Créer des statistiques fake
        $statsData = [
            // Stats validées (70%)
            [
                'user_id' => $users->random()->id,
                'goals' => 3,
                'assists' => 2,
                'goals_against' => 1,
                'matches_played' => 1,
                'date' => now()->subDays(1),
                'validated_by_admin' => true,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => $users->random()->id,
                'goals' => 1,
                'assists' => 3,
                'goals_against' => 0,
                'matches_played' => 1,
                'date' => now()->subDays(2),
                'validated_by_admin' => true,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $users->random()->id,
                'goals' => 2,
                'assists' => 1,
                'goals_against' => 2,
                'matches_played' => 1,
                'date' => now()->subDays(3),
                'validated_by_admin' => true,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => $users->random()->id,
                'goals' => 0,
                'assists' => 1,
                'goals_against' => 0,
                'matches_played' => 1,
                'date' => now()->subDays(4),
                'validated_by_admin' => true,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'user_id' => $users->random()->id,
                'goals' => 4,
                'assists' => 0,
                'goals_against' => 3,
                'matches_played' => 1,
                'date' => now()->subDays(5),
                'validated_by_admin' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],

            // Stats en attente de validation (30%)
            [
                'user_id' => $users->random()->id,
                'goals' => 2,
                'assists' => 1,
                'goals_against' => 1,
                'matches_played' => 1,
                'date' => now()->today(),
                'validated_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users->random()->id,
                'goals' => 1,
                'assists' => 2,
                'goals_against' => 0,
                'matches_played' => 1,
                'date' => now()->today(),
                'validated_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users->random()->id,
                'goals' => 3,
                'assists' => 0,
                'goals_against' => 2,
                'matches_played' => 1,
                'date' => now()->subHours(2),
                'validated_by_admin' => false,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
        ];

        // Insérer les données
        Stat::insert($statsData);

        $this->command->info('Statistiques fake créées avec succès!');
        $this->command->info('Stats validées: ' . collect($statsData)->where('validated_by_admin', true)->count());
        $this->command->info('Stats en attente: ' . collect($statsData)->where('validated_by_admin', false)->count());
    }
}
