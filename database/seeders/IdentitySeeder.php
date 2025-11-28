<?php

namespace Database\Seeders;

use App\Models\Identity;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    public function run(): void
    {
        Identity::create([
            'name' => 'FC Example',
            'logo' => '/images/fc-example.png',
        ]);
    }
}
