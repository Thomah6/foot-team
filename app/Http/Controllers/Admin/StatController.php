<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stat;    
use App\Models\User;    
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


public function validateStat(Stat $stat)
{
    $stat->update(['validated_by_admin' => true]);

    return back()->with('success', 'Stat validée');
}

public function classementsGoals()
{
    $buteurs = User::withSum(['stats as total_goals' => function($q) {
        $q->where('validated_by_admin', true);
    }], 'goals')
    ->having('total_goals', '>=', 2)
    ->orderByDesc('total_goals')
    ->get();

    return Inertia::render('Admin/Stats/Classements', [
        'buteurs' => $buteurs
    ]);
}


public function classementsAssists()
{
    $passeurs  = User::withSum(['stats as total_goals' => function($q) {
        $q->where('validated_by_admin', true);
    }], 'goals')
    ->having('total_goals', '>=', 2)
    ->orderByDesc('total_goals')
    ->get();

    return Inertia::render('Admin/Stats/Classements', [
        'passeurs' => $passeurs
    ]);
}

public function classementsGardiens(){
    $gardiens = User::withSum(['stats as total_goals' => function($q) {
        $q->where('validated_by_admin', true);
    }], 'goals')
    ->having('total_goals', '>=', 2)
    ->orderByDesc('total_goals')
    ->get();

    return Inertia::render('Admin/Stats/Classements', [
        'gardiens' => $gardiens
    ]);
}





}