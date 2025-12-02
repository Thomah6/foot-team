<?php

namespace App\Http\Controllers;

use App\Models\PlayerOfTheMonth;
use App\Models\Stat;
use App\Models\News;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $identity = \App\Models\Identity::first();

        // Joueur du mois actif
        $player = PlayerOfTheMonth::with('user')
            ->where('is_active', true)
            ->latest('month')
            ->first();

        // Stats du joueur du mois actif
        $stats = null;
        if ($player) {
            $month = \Carbon\Carbon::parse($player->month);
            $stats = Stat::where('user_id', $player->user_id)
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('validated_by_admin', true)
                ->first();
        }

        // 4 derniers joueurs du mois précédents
        $previousPlayers = PlayerOfTheMonth::with('user')
            ->where('is_active', false)
            ->orderBy('month', 'desc')
            ->take(4)
            ->get();

        // News à la une
        $featured = News::where('is_published', true)
            ->latest('created_at')
            ->first();

            // dd($featured);

        return Inertia::render('Dashboard', [
            'identity' => $identity,
            'player' => $player,
            'stats' => $stats,
            'previousPlayers' => $previousPlayers,
            'featured' => $featured,
        ]);
    }
}
