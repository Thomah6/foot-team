<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
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
        User::factory(10)->create();
        Team::factory(10)->create();
        Stat::factory(10)->create();

        
    }
}
