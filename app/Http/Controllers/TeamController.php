<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    /**
     * Liste les équipes (lecture seule)
     */
    public function index()
    {
        $user = auth()->user();
        $isAdmin = ($user->role === 'admin') || (method_exists($user, 'hasRole') && $user->hasRole('admin'));

        return Inertia::render("Teams/Index", [
            'teams' => Team::with('users')->get(),
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Affiche le formulaire de gestion des équipes
     */
    public function manage()
    {
        return Inertia::render("Teams/Manage", [
            'teams' => Team::with('users')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
        ]);
        Team::create($validated);
        return redirect()->route('admin.teams.index')->with('success', '');
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        $team->update($validated);

        return back();
    }

    public function destroy(Team $id)
    {
        $id->delete();
        return back();
    }

    public function affectPage(Team $team)
    {
        $all = User::all();
        $assigned = $team->users;
        $availlable = $all->whereNotIn('id', $assigned->pluck('id'))->values()->all();

        return inertia('Teams/AffectPage', [
            'team' => $team,
            'assigned'=>$assigned,
            'available'=>$availlable,
        ]);
    }


    //Pour sauvegarder l'affectation
    public function saveAffect(Request $request, Team $team)
    {

        $request->validate([
            'members' => 'array'
        ]);

        $team->users()->sync($request->members);

         return redirect()->route('admin.teams.index')->with('success', 'Affectation mise à jour');
    }

    public function show(Team $team)
    {
        // Charges les membres liés à l'équipe
        $team->load('users');
        $user = auth()->user();
        $isAdmin = ($user->role === 'admin') || (method_exists($user, 'hasRole') && $user->hasRole('admin'));

        return Inertia::render('Teams/Show', [
            'team' => $team,
            'isAdmin' => $isAdmin,
        ]);
    }

}
