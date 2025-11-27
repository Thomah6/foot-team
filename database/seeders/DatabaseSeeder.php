<?php

namespace Database\Seeders;

use App\Models\Reflection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
