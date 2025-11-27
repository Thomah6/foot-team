<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request\StatRequest;
use App\Models\Team;
use App\Models\TeamStat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamStatController extends Controller
{
    /**
     * Afficher la liste de toutes les stats des équipes
     */

    public function index(){
        $stats = TeamStat::with('team')->orderBy('month','desc')->orderBy('team_id')->paginate(15);

        return Inertia::render('Admin/TeamStats/Index',[
            'stats' => $stats,
            'teamsCount' => Team::count(),
        ]);
    }

    /**
     * Afficher le formulaire de création
     */

    public function create(){
        $teams = Team::all(['id','name']);

        return Inertia::render('Admin/TeamStats/CreateEdit',[
            'teams' => $teams,
            'stat' => null,
            'isEditing' => false,
            'currentMonth' => now()->startOfMonth()->toDateString()
        ]);
    }

    public function store(StatRequest $request){
        $validatedData = $request->validated();

        //Convertir la date pour extraire le mois et l'année
        $monthDate = Carbon::parse($validatedData['month']);
        $monthStart = $monthDate->startOfMonth()->toDateString();
        $monthEnd = $monthDate->endOfMonth()->toDateString();

        //On vérifie qu'il n'existe pas déjà une stat pour cette équipe ce mois ,sinon envoyer un message d'erreur pour la remplacer
        $existingMonth = TeamStat::where('team_id',$validatedData['team_id'])->whereBetween('month',[$monthStart,$monthEnd])->first();

        //envoyer le message pour la remplacer
        if($existingMonth && !$request->has('override')) {
            return back()->withErrors([
                'duplicate' => 'Une stat existe déjà pour cette équipe ce mois-ci.Voulez-vous la remplacer?', 
            ]);
        }

        //S'il y a déjà une stat pour ce mois et  la requête contient override supprimer l'ancienne
        if($existingMonth && $request->has('override')){
            $existingMonth->delete();
        }

        TeamStat::create($validatedData);

        return redirect()->route('admin.team-stats.index')->with('success','Stat d\'équipe créée avec succès');
    }

    /**
     * Afficher le formulaire d'édition
     */

    public function edit(TeamStat $teamStat){
        $teams = Team::all(['id','name']);

        return Inertia::render('Admin/TeamStats/CreateEdit',[
            'teams' => $teams,
            'stat' => $teamStat,
            'isEditing' => true,
            'currentMonth' => now()->startOfMonth()->toDateString(),
        ]);
    }

    /**
     * Fonction pour mettre à jour une stat D'équipe
     */

    public function update(StatRequest $request, TeamStat $teamStat){
        $validatedData = $request->validated();
        $teamStat->update($validatedData);

         return redirect()->route('admin.team-stats.index')
            ->with('success', 'Stat d\'équipe mise à jour avec succès');

    }

    /**
     * Supprimer une stat d'équipe
     */
    public function destroy(TeamStat $teamStat){
        $teamName = $teamStat->team->name;
        $month = Carbon::parse($teamStat->month)->format('F Y');

        // supprimer la stat
        $teamStat->delete();

        return redirect()->route('admin.team-stats.index')
            ->with('success', "Stat de {$teamName} ({$month}) supprimée");

    }

    /**
     * Afficher les stats filtrées par équipe
     */

    public function byTeam(Team $team){
        $stats = $team->stats()->orderBy('month','desc')->get();

        return Inertia::render('Admin/TeamStats/TeamHistory',[
            'team' => $team,
            'stats' => $stats
        ]);
    }

    /**
     * Récupérer les stats du mois courant par équipe
     */
    public function currentMonthStats(){
        $currentMonth = now()->startOfMonth();

        $stats = TeamStat::with('team')->where('month',$currentMonth->toDateString())->orderBy('team_id')->get()->map(function($stat){
           return [
             'team_id' => $stat->team_id,
             'team_name' => $stat->team->name,
             'wins' => $stat->wins,
             'losses' => $stat->losses,
             'draws' => $stat->draws,
             'total_matches' => $stat->total_matches,
             'points' => $stat->points,
             'win_rate' => $stat->win_rate,

           ];
        });

        return response()->json($stats);
    }

    /***
     * Validation en masse des stats
     */

    public function bulkValidate(Request $request){
        $validated = $request->validate([
            'stat_ids' =>'required|array',
            'stat_ids.*' => 'exists:team_stats,id'
        ]);

        TeamStat::whereIn('id',$validated['stat_ids'])->update(['validated' => true]);

         return back()->with('success', 'Stats validées avec succès');
    }
}
