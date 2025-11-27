<?php

namespace Database\Seeders;

use App\Models\Reflection;
use App\Models\Team;
use App\Models\User;
use App\Models\Stat;
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

        User::factory(10)->create();
        Stat::factory(20)->create();
        Team::factory(20)->create();
    }
}
