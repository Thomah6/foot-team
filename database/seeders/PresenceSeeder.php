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
        // Récupérer uniquement les utilisateurs factices (exclure les comptes connus/fixes)
        $excludedEmails = [
            'admin@test.com',
            'bureau@test.com',
            'jean.dupont@test.com',
            'alice.martin@test.com',
            'bob.leroy@test.com',
        ];

        $users = User::whereNotIn('email', $excludedEmails)->get();

        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }

        $currentMonth = Carbon::now();
        $startDate = $currentMonth->clone()->startOfMonth();
        $endDate = $currentMonth->clone()->endOfMonth();

        // Trouver l'utilisateur 'bureau' s'il existe (pour simuler des déclarations faites par le bureau)
        $bureauUser = User::where('role', 'bureau')->first();

        // Créer des présences pour chaque utilisateur du mois actuel
        foreach ($users as $user) {
            $currentDate = $startDate->clone();

            while ($currentDate->lte($endDate)) {
                // Passer les weekends (samedi et dimanche)
                if ($currentDate->isWeekday()) {
                    // Décider du statut de la journée
                    $roll = rand(1, 100);

                    // Par défaut pas de déclarant externe
                    $declaredBy = null;
                    // 12% des déclarations peuvent être faites par le bureau (pour les autres utilisateurs)
                    if ($bureauUser && $user->role !== 'bureau' && rand(1, 100) <= 12) {
                        $declaredBy = $bureauUser->id;
                    }

                    if ($roll <= 80) {
                        // Présent (80%) — validé 65% du temps, sinon en attente
                        $validated = rand(1, 100) <= 65;

                        Presence::create([
                            'user_id' => $user->id,
                            'date' => $currentDate->toDateString(),
                            'present' => true,
                            'validated_by_admin' => $validated,
                            'declared_by_user_id' => $declaredBy,
                        ]);
                    } elseif ($roll <= 92) {
                        // Absence validée (12%)
                        Presence::create([
                            'user_id' => $user->id,
                            'date' => $currentDate->toDateString(),
                            'present' => false,
                            'validated_by_admin' => true,
                            'declared_by_user_id' => $declaredBy,
                        ]);
                    } else {
                        // Absence non validée / en attente (8%) — peut représenter un "refus" ou cas incertain
                        Presence::create([
                            'user_id' => $user->id,
                            'date' => $currentDate->toDateString(),
                            'present' => false,
                            'validated_by_admin' => false,
                            'declared_by_user_id' => $declaredBy,
                        ]);
                    }
                }

                $currentDate->addDay();
            }
        }

        $this->command->info('✅ Présences du mois créées avec succès !');
    }
}
