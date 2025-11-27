<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            ['name' => 'FC Paris', 'color' => '#1e293b', 'description' => 'Club de Paris'],
            ['name' => 'Olympique Lyonnais', 'color' => '#eab308', 'description' => 'Club de Lyon'],
            ['name' => 'AS Marseille', 'color' => '#38bdf8', 'description' => 'Club de Marseille'],
            ['name' => 'Stade Rennais', 'color' => '#ef4444', 'description' => 'Club de Rennes'],
            ['name' => 'LOSC Lille', 'color' => '#f43f5e', 'description' => 'Club de Lille'],
            ['name' => 'FC Nantes', 'color' => '#facc15', 'description' => 'Club de Nantes'],
            ['name' => 'AS Monaco', 'color' => '#f87171', 'description' => 'Club de Monaco'],
            ['name' => 'Montpellier HSC', 'color' => '#34d399', 'description' => 'Club de Montpellier'],
            ['name' => 'OGC Nice', 'color' => '#a3e635', 'description' => 'Club de Nice'],
            ['name' => 'Toulouse FC', 'color' => '#818cf8', 'description' => 'Club de Toulouse'],
        ];
        foreach ($teams as $data) {
            Team::updateOrCreate(['name' => $data['name']], [
                'color' => $data['color'],
                'description' => $data['description'],
            ]);
        }
    }
}
