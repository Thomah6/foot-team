<?php

namespace App\Console\Commands;

use App\Models\Presence;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ValidatePresencesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:validate {--month=} {--auto}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Validate presences automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ? Carbon::parse($this->option('month')) : Carbon::now();
        $auto = $this->option('auto');

        $startDate = $month->clone()->startOfMonth();
        $endDate = $month->clone()->endOfMonth();

        // Récupérer les présences du mois
        $presences = Presence::whereBetween('date', [$startDate, $endDate])
            ->where('present', true)
            ->where('validated_by_admin', false)
            ->get();

        $this->info("📅 Présences en attente de validation pour {$month->format('F Y')}");
        $this->line('─' . str_repeat('─', 50));

        if ($presences->isEmpty()) {
            $this->warn('✅ Aucune présence en attente de validation');
            return;
        }

        $this->line("🔍 Présences trouvées : {$presences->count()}");

        if ($auto) {
            // Validation automatique
            $presences->each(function ($presence) {
                $presence->update(['validated_by_admin' => true]);
            });

            $this->info("✅ {$presences->count()} présences validées automatiquement");
            return;
        }

        // Afficher les présences et demander confirmation
        $this->table(['ID', 'Membre', 'Date', 'Statut'], $presences->map(function ($p) {
            return [
                $p->id,
                $p->user->name,
                $p->date,
                'En attente',
            ];
        })->toArray());

        if ($this->confirm('Valider toutes ces présences ?')) {
            $presences->each(function ($presence) {
                $presence->update(['validated_by_admin' => true]);
            });

            $this->info("✅ {$presences->count()} présences validées avec succès");
        } else {
            $this->line('❌ Opération annulée');
        }
    }
}
