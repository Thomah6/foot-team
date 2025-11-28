<?php

namespace App\Helpers;

use App\Models\Team;
use App\Models\TeamStat;

class TeamStatHelper{
    /**
     * Obtenir les classements des équipes pour le mois courant
     */

    public static function getMonthlyRankings(){
        $currentMonth = now()->startOfMonth()->toDateString();

        return TeamStat::with('team')->where('month',$currentMonth)->orderByDesc('wins')->orderByDesc('draws')->orderByDesc('losses')->get()->map(function($stat){
               return [
                    'team' => $stat->team,
                    'wins' => $stat->wins,
                    'losses' => $stat->losses,
                    'draws' => $stat->draws,
                    'total_matches' => $stat->total_matches,
                    'points' => $stat->points,
                    'win_rate' => $stat->win_rate,
                ];

        });
    }

    /**
     * Obtenir les performances d'une équipe
     */

    public static function getTeamPerformance($teamId){
         $stats = TeamStat::where('team_id',$teamId)->orderBy('month','desc')->get();

         return [
            'total_wins' => $stats->sum('wins'),
            'total_losses' => $stats->sum('losses'),
            'total_draws' => $stats->sum('draws'),
            'total_points' => $stats->sum(fn($s) => ($s->wins * 3) + $s->draws),
            'average_win_rate' => $stats->isEmpty() ? 0 :round($stats->sum('wins') / $stats->sum(fn($s) => $s->wins + $s->losses + $s->draws) * 100,2),
         ];
    }
}