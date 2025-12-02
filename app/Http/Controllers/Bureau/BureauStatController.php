<?php

namespace App\Http\Controllers\Bureau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Stat;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;

class BureauStatController extends Controller
{
	public function index(Request $request)
	{
		//page d’accueil des statistiques du bureau
		$totalStats = Stat::count();
		$activePlayers = User::has('stats')->count();
		$latestStats = Stat::with('user')->latest()->take(6)->get();
        $avatar = 'storage/avatars';
		return Inertia::render('Bureau/Index', [
			'totalStats' => $totalStats,
			'activePlayers' => $activePlayers,
			'latestStats' => $latestStats,
			'avatar' => $avatar,
		]);
	}

	public function leaderboards(Request $request)
	{
	//Page générique des classements – retourne les classements calculés afin que Vue puisse les afficher
		$goalLeaders = User::withSum(['stats as total_goals' => function ($q) {
			$q->where('validated_by_admin', true);
		}], 'goals')
		->with('teams')
		->orderByDesc('total_goals')
		->take(12)
		->get();

		$assistLeaders = User::withSum(['stats as total_assists' => function ($q) {
			$q->where('validated_by_admin', true);
		}], 'assists')
		->with('teams')
		->orderByDesc('total_assists')
		->take(12)
		->get();

		$goalkeeperLeaders = User::withSum(['stats as total_goals_against' => function ($q) {
			$q->where('validated_by_admin', true);
		}], 'goals_against')
		->with('teams')
		->with('stats')
		->orderBy('total_goals_against') // fewer goals against = better
		->take(10)
		->get();
		

		return Inertia::render('Bureau/Leaderboards', [
			'goals' => $goalLeaders,
			'assists' => $assistLeaders,
			'goalkeepers' => $goalkeeperLeaders,
		]);
	}

	public function goalLeaders(Request $request)
	{
		return $this->leaderboards($request);
	}

	public function assistLeaders(Request $request)
	{
		return $this->leaderboards($request);
	}

	public function goalkeeperLeaders(Request $request)
	{
		return $this->leaderboards($request);
	}

	public function memberStats(User $user, Request $request)
	{
		// Filters: month / year or from/to
		$query = $user->stats()->where('validated_by_admin', true);

		// parse query params
		if ($request->filled('month') && $request->filled('year')) {
			$month = (int) $request->get('month');
			$year = (int) $request->get('year');
			$query->whereMonth('date', $month)->whereYear('date', $year);
		} elseif ($request->filled('from') && $request->filled('to')) {
			$from = Carbon::parse($request->get('from'))->startOfDay();
			$to = Carbon::parse($request->get('to'))->endOfDay();
			$query->whereBetween('date', [$from, $to]);
		} else {
			// default to last 12 months
			$query->where('date', '>=', Carbon::now()->subMonths(12));
		}

		$stats = $query->orderByDesc('date')->get();

		// Totals
		$totals = [
			'goals' => $stats->sum('goals'),
			'assists' => $stats->sum('assists'),
			'goals_against' => $stats->sum('goals_against'),
			'matches_played' => $stats->sum('matches_played'),
		];

		// Build monthly series for charts (group by yyyy-mm)
		$series = $stats->groupBy(function ($item) {
			return Carbon::parse($item->date)->format('Y-m');
		})->map(function ($group) {
			return [
				'goals' => $group->sum('goals'),
				'assists' => $group->sum('assists'),
				'matches' => $group->sum('matches_played'),
			];
		});

		// Ensure chronological order
		$months = $series->keys()->sort()->values()->all();
		$goalsPoints = collect($series->values())->map(fn($v) => $v['goals'])->all();
		$assistsPoints = collect($series->values())->map(fn($v) => $v['assists'])->all();

		return Inertia::render('Bureau/MemberStats', [
			'user' => $user,
			'stats' => $stats,
			'totals' => $totals,
			'chart' => [
				'labels' => $months,
				'goals' => $goalsPoints,
				'assists' => $assistsPoints,
                
			],
		]);
	}
}
