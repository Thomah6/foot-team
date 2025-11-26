<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VoteController extends Controller
{
    /**
     * Affiche les options de vote.
     */
    public function index()
    {
        $options = [
            ['id' => 1, 'title' => 'Focus on new feature development', 'description' => 'Dedicate the majority of resources to building out the new reporting module.'],
            ['id' => 2, 'title' => 'Prioritize bug fixes and performance improvements', 'description' => 'Address the backlog of reported issues to enhance stability and user experience.'],
            ['id' => 3, 'title' => 'Allocate time for technical debt reduction', 'description' => 'Refactor legacy code and upgrade dependencies to improve maintainability.'],
        ];

        return Inertia::render('Reflections/Index', [
            'options' => $options,
            'isAdmin' => Auth::user()->role === 'admin',
            'isVoteEnded' => now()->greaterThanOrEqualTo('2025-11-26 17:50:00'), // Exemple de délai
        ]);
    }

    /**
     * Enregistre un vote.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reflection_id' => 'required|exists:reflections,id',
            'value' => 'required|integer',
        ]);

        Vote::updateOrCreate(
            ['reflection_id' => $request->reflection_id, 'user_id' => Auth::user()->id],
            ['value' => $request->value]
        );

        return response()->json(['message' => 'Vote enregistré avec succès.']);
    }

    /**
     * Valide le vote (admin uniquement).
     */
    public function validateVote(Request $request)
    {
        if (!Auth::user()->role === 'admin') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Logique pour valider le vote (exemple : mettre à jour le statut de la réflexion)
        return response()->json(['message' => 'Vote validé avec succès.']);
    }
}
