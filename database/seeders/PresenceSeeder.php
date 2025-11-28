<?php

namespace Database\Seeders;

use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresenceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }

        $currentMonth = Carbon::now();
        $startDate = $currentMonth->clone()->startOfMonth();
        $endDate = $currentMonth->clone()->endOfMonth();

        // Créer des présences pour chaque utilisateur du mois actuel
        foreach ($users as $user) {
            $currentDate = $startDate->clone();

            while ($currentDate->lte($endDate)) {
                // Passer les weekends (samedi et dimanche)
                if ($currentDate->isWeekday()) {
                    // Créer une présence avec 85% de chance d'être présent
                    if (rand(1, 100) <= 85) {
                        // 70% validées, 30% en attente
                        $validated = rand(1, 100) <= 70;

                        Presence::create([
                            'user_id' => $user->id,
                            'date' => $currentDate->toDateString(),
                            'present' => true,
                            'validated_by_admin' => $validated,
                        ]);
                    } else {
                        // Absence
                        Presence::create([
                            'user_id' => $user->id,
                            'date' => $currentDate->toDateString(),
                            'present' => false,
                            'validated_by_admin' => true, // Les absences sont toujours validées
                        ]);
                    }
                }

                $currentDate->addDay();
            }
        }

        $this->command->info('✅ Présences du mois créées avec succès !');
    }
}
