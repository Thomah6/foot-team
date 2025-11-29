<?php

namespace App\Http\Controllers;

use App\Models\PlayerOfTheMonth;
use Illuminate\Http\Request;
use App\Models\Stat;
use Inertia\Inertia;

class PlayerOfTheMonthController extends Controller
{
    /**
     * Affiche le joueur actif du mois et les archives
     */

    public function index()
    {
        // Récupère le joueur du mois actif
        $player = PlayerOfTheMonth::with('user')
            ->where('is_active', true)
            ->latest('month')
            ->first();

        $stats = null;

        // 4 derniers joueurs du mois précédents (hors actif)
        $previousPlayers = PlayerOfTheMonth::with('user')
            ->where('is_active', false)
            ->orderBy('month', 'desc')
            ->take(4)
            ->get();

        if ($player) {
            // Convertit la date du mois
            $month = \Carbon\Carbon::parse($player->month);

            // Récupère les stats validées pour ce mois
            $stats = Stat::where('user_id', $player->user_id)
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('validated_by_admin', true)
                ->first();
        }

        return Inertia::render('Dashboard', [
            'player' => $player,
            'previousPlayers' => $previousPlayers,
            'stats'  => $stats,
        ]);
    }
}
