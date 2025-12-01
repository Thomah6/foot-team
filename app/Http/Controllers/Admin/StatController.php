<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stat;    
use App\Models\User;    
use App\Models\PlayerOfTheMonth;
use Inertia\Inertia;      
class StatController extends Controller
{
    //
public function index()
{
    return Inertia::render('Stats/Index', [
        'pendingCount' => Stat::where('validated_by_admin', false)->count(),
        'validatedCount' => Stat::where('validated_by_admin', true)->count(),
        'lastStats' => Stat::with('user')->latest()->take(5)->get(),
    ]);
}

public function publicIndex()
{
    return Inertia::render('Stats/Public', [
        'validatedCount' => Stat::where('validated_by_admin', true)->count(),
        'topStats' => Stat::with('user')->where('validated_by_admin', true)->latest()->take(10)->get(),
        'topButeurs' => User::withSum(['stats as total_goals' => function($q) {
            $q->where('validated_by_admin', true);
        }], 'goals')->orderByDesc('total_goals')->take(5)->get(),
    ]);
}
  
    public function store(Request $request)
{
    $data = $request->validate([
        'user_id' => 'required|exists:users,id',
        'goals' => 'integer|min:0',
        'assists' => 'integer|min:0',
        'goals_against' => 'integer|min:0',
        'matches_played' => 'integer|min:1',
        'date' => 'required|date',
    ]);

    Stat::create($data);

    return back()->with('success', 'Stat ajoutée (en attente de validation admin)');
    
}



public function pending(){
    $stats = Stat::where(
        'validated_by_admin', false
    )->with('user')->orderBy('date', 'desc')->get();

    return Inertia::render('Admin/Stat/Pending', [
        'stats' => $stats
    ]);
}


public function create( Request $request)
{
     $users = user::all();
    return Inertia::render('Admin/CreateStats', ['success'=> $request->session()->get('success'),'users'=>$users ]);
}






public function validateStat(Stat $stat)
{
    $stat->update(['validated_by_admin' => true]);

    return redirect()->route('admin.stats.index')->with('success', 'Stat validée');
}

public function rejectStat(Stat $stat)
{
    $stat->delete();

    return redirect()->route('admin.stats.index')->with('success', 'Stat rejetée et supprimée');
}

public function classementsIndex()
{
    return Inertia::render('Classements');
}

public function adminClassementsIndex()
{
    return Inertia::render('Admin/Stats/Classements');
}

public function classementsGoals()
{
    $buteurs = User::withSum(['stats as total_goals' => function($q) {
        $q->where('validated_by_admin', true);
    }], 'goals')
    ->having('total_goals', '>=', 2)
    ->orderByDesc('total_goals')
    ->get();

    return Inertia::render('Admin/Stats/ClassementButeurs', [
        'buteurs' => $buteurs
    ]);
}

public function classementGeneral()
{
    $currentMonth = now()->format('Y-m');
    $currentMonthNum = now()->month;
    $currentYear = now()->year;
    $currentMonthDate = now()->startOfMonth()->format('Y-m-d'); // Format date complet
    
    // 1. Calculer le classement du mois en cours
    $classement = User::with(['stats' => function($query) use ($currentMonthNum, $currentYear) {
        $query->where('validated_by_admin', true)
              ->whereMonth('date', $currentMonthNum)
              ->whereYear('date', $currentYear);
    }])
    ->get()
    ->map(function($user) use ($currentMonth, $currentMonthNum, $currentYear) {
        $monthlyStats = $user->stats->filter(function($stat) use ($currentMonthNum, $currentYear) {
            return $stat->validated_by_admin && 
                   $stat->date->month == $currentMonthNum && 
                   $stat->date->year == $currentYear;
        });
        
        $totalGoals = $monthlyStats->sum('goals');
        $totalAssists = $monthlyStats->sum('assists');
        $points = $totalGoals + ($totalAssists / 2);
        
        return [
            'user' => $user,
            'total_goals' => $totalGoals,
            'total_assists' => $totalAssists,
            'points' => $points,
            'matches_played' => $monthlyStats->sum('matches_played'),
            'month' => $currentMonth
        ];
    })
    ->filter(function($player) {
        return $player['matches_played'] > 0;
    })
    ->sortByDesc('points')
    ->take(10)
    ->values();

    // 2. Désigner automatiquement le joueur du mois s'il y a un classement
    if ($classement->isNotEmpty()) {
        $winner = $classement->first();
        
        // Sauvegarder le joueur du mois
        PlayerOfTheMonth::updateOrCreate(
            ['month' => $currentMonthDate], // Utilise le format date complet
            [
                'user_id' => $winner['user']->id,
                'reason' => $this->generateMotivation($winner),
                'is_active' => true
            ]
        );
    }

    return Inertia::render('Admin/Stats/ClassementGeneral', [
        'classement' => $classement,
        'current_month' => $currentMonth,
        'is_player_of_month' => true
    ]);
}

private function generateMotivation($winner)
{
    $points = $winner['points'];
    $goals = $winner['total_goals'];
    $assists = $winner['total_assists'];
    $matches = $winner['matches_played'];
    
    return "Performance exceptionnelle ce mois-ci avec {$points} points ({$goals} buts et {$assists} passes décisives) en {$matches} matchs. Une contribution précieuse pour l'équipe !";
}


public function classementsAssists()
{
    $passeurs = User::withSum(['stats as total_assists' => function($q) {
        $q->where('validated_by_admin', true);
    }], 'assists')
    ->having('total_assists', '>=', 2)
    ->orderByDesc('total_assists')
    ->get();

    return Inertia::render('Admin/Stats/ClassementPasseurs', [
        'passeurs' => $passeurs
    ]);
}

public function classementsGardiens()
{
    // Classement des gardiens : moins de buts encaissés = meilleur
    $gardiens = User::withSum(['stats as total_goals_against' => function($q) {
        $q->where('validated_by_admin', true);
    }], 'goals_against')
    ->whereHas('stats', function($q) {
        $q->where('validated_by_admin', true)
          ->where('goals_against', '>', 0);
    })
    ->orderBy('total_goals_against', 'asc') // Moins encaissé = meilleur
    ->get();

    return Inertia::render('Admin/Stats/ClassementGardiens', [
        'gardiens' => $gardiens
    ]);
}

/**
 * 🏆 Joueur du Mois - Public
 */
public function currentPlayerOfMonth()
{
    // Debug temporaire
    
    $currentMonthDate = now()->startOfMonth()->format('Y-m-d');
    $player = PlayerOfTheMonth::with('user')
        ->where('month', $currentMonthDate)
        ->first();
        
        $stats = Stat::where('user_id', $player->user_id)
        ->whereMonth('date', date('m'))
        ->whereYear('date', date('Y'))
        ->where('validated_by_admin', true)
        ->get();
        
    // Si pas encore généré ce mois-ci, prendre le mois précédent
    if (!$player) {
        $player = PlayerOfTheMonth::with('user')
            ->orderByDesc('month')
            ->first();
    }
    
    // Si toujours aucun joueur, créer un objet par défaut
    if (!$player) {
        $player = null;
    }
        
    return Inertia::render('PlayerOfMonth/Current', [
        'player' => $player,
        'stats'=>$stats
    ]);
}

public function historyPlayerOfMonth()
{
    $players = PlayerOfTheMonth::with('user')
        ->orderByDesc('month')
        ->paginate(12);
        
    return Inertia::render('PlayerOfMonth/History', [
        'players' => $players
    ]);
}

public function monthlyStats($month)
{
    // Convertir le mois en format date
    $monthDate = date('Y-m-01', strtotime($month));
    
    $player = PlayerOfTheMonth::with('user')
        ->where('month', $monthDate)
        ->firstOrFail();
        
    $stats = Stat::where('user_id', $player->user_id)
        ->whereMonth('date', date('m', strtotime($month)))
        ->whereYear('date', date('Y', strtotime($month)))
        ->where('validated_by_admin', true)
        ->get();
        
    return Inertia::render('PlayerOfMonth/Stats', [
        'player' => $player,
        'stats' => $stats,
        'month' => $month
    ]);
}




}