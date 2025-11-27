<?php

namespace App\Console\Commands;

use App\Models\Stat;
use App\Models\PlayerOfTheMonth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetMonthlyStats extends Command
{
    protected $signature = 'stats:reset-monthly {month?}';
    protected $description = 'Réinitialise les stats du mois et désigne le joueur du mois';

    public function handle()
    {
        $month = $this->argument('month') ?? now()->subMonth()->format('Y-m');
        
        $this->info("🏆 Calcul du Joueur du Mois pour: {$month}");
        
        // 1. Calculer le classement du mois
        $classement = $this->calculateMonthlyRanking($month);
        
        if ($classement->isNotEmpty()) {
            // 2. Sauvegarder le joueur du mois
            $this->savePlayerOfMonth($classement->first(), $month);
            
            // 3. Afficher les résultats
            $this->displayResults($classement, $month);
        } else {
            $this->warn("Aucune statistique trouvée pour le mois: {$month}");
        }
        
        $this->info("✅ Joueur du mois désigné avec succès!");
    }
    
    private function calculateMonthlyRanking($month)
    {
        $users = User::whereHas('stats', function($query) use ($month) {
            $query->where('validated_by_admin', true)
                  ->whereMonth('date', date('m', strtotime($month)))
                  ->whereYear('date', date('Y', strtotime($month)));
        })->get();
        
        return $users->map(function($user) use ($month) {
            $stats = $user->stats()
                ->where('validated_by_admin', true)
                ->whereMonth('date', date('m', strtotime($month)))
                ->whereYear('date', date('Y', strtotime($month)))
                ->get();
            
            $totalGoals = $stats->sum('goals');
            $totalAssists = $stats->sum('assists');
            $totalMatches = $stats->sum('matches_played');
            $points = $totalGoals + ($totalAssists / 2);
            
            return [
                'user' => $user,
                'total_goals' => $totalGoals,
                'total_assists' => $totalAssists,
                'points' => $points,
                'matches_played' => $totalMatches,
                'points_per_match' => $totalMatches > 0 ? $points / $totalMatches : 0
            ];
        })
        ->filter(function($player) {
            return $player['matches_played'] > 0;
        })
        ->sortByDesc('points')
        ->take(10)
        ->values();
    }
    
    private function savePlayerOfMonth($winner, $month)
    {
        PlayerOfTheMonth::updateOrCreate(
            ['month' => $month],
            [
                'user_id' => $winner['user']->id,
                'votes_count' => 0, // Pas de votes pour auto
                'motivation' => $this->generateMotivation($winner),
                'photo_url' => $winner['user']->avatar,
                'auto_generated' => true
            ]
        );
        
        $this->info("🎉 Joueur du mois: {$winner['user']->name} avec {$winner['points']} points");
    }
    
    private function generateMotivation($winner)
    {
        $points = $winner['points'];
        $goals = $winner['total_goals'];
        $assists = $winner['total_assists'];
        $matches = $winner['matches_played'];
        
        return "Performance exceptionnelle ce mois-ci avec {$points} points ({$goals} buts et {$assists} passes décisives) en {$matches} matchs. Une contribution précieuse pour l'équipe !";
    }
    
    private function displayResults($classement, $month)
    {
        $this->newLine();
        $this->info("📊 Classement du mois - {$month}");
        $this->newLine();
        
        $headers = ['Pos', 'Joueur', 'Points', 'Buts', 'Passes', 'Matchs'];
        $rows = [];
        
        foreach ($classement as $index => $player) {
            $medal = '';
            if ($index === 0) $medal = '🥇';
            elseif ($index === 1) $medal = '🥈';
            elseif ($index === 2) $medal = '🥉';
            
            $rows[] = [
                $medal . ' ' . ($index + 1),
                $player['user']->name,
                number_format($player['points'], 1),
                $player['total_goals'],
                $player['total_assists'],
                $player['matches_played']
            ];
        }
        
        $this->table($headers, $rows);
    }
}
