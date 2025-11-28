<?php

namespace Database\Seeders;

use App\Models\Comment;
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
        // User::factory(10)->create();
        Reflection::factory(40)->create();
        Comment::factory(100)->create();

    }
}
