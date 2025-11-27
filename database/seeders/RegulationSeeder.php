<?php

namespace Database\Seeders;

use App\Models\Regulation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $defaults = ['Respect','Attitude', 'Entraînements', 'Matchs', 'MATÉRIEL ET ÉQUIPEMENTS', 'HYGIÈNE ET SANTÉ' ,'ENGAGEMENT SPORTIF','SANCTIONS', 'COMMUNICATION AVEC LE STAFF','ENGAGEMENT DU JOUEUR'];

        foreach ($defaults as $title) {
            Regulation::firstOrCreate([
                'title' => $title,
                'content' => null,
            ]);
        }
    }
}
