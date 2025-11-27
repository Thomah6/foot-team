<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Reflection;
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
            ['id' => 1, 'option' => 'POUR'],
            ['id' => 2, 'option' => 'CONTRE'],
        ];
        $reflectionId= 1;
        $existingVotes = Vote::where('reflection_id', $reflectionId)->where('user_id', Auth::id())->first();

        return Inertia::render('Reflections/Index', [
            'options' => $options,
            'isAdmin' => Auth::user()->role === 'admin',
            'isVoteEnded' => now()->greaterThanOrEqualTo('2025-11-27 10:06:00'), // Exemple de délai
            'existingVote' => $existingVotes? $existingVotes->value : null,
        ]);
    }

    /**
     * Enregistre un vote.
     */
    public function store(Request $request)
    {
    
        $request->validate([
            'reflection_id' => 'required|exists:reflections,id',
            'value' => 'required|integer|in:1,-1',
        ]);

        $reflection = Reflection::findOrFail($request->reflection_id);

        // Vérifier si l'utilisateur a déjà voté pour cette réflexion
        $existingVote = Vote::where('reflection_id', $reflection->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            // Mettre à jour le vote existant
            $existingVote->update(['value' => $request->value]);
        } else {
            // Créer un nouveau vote
            Vote::create([
                'reflection_id' => $reflection->id,
                'user_id' => Auth::id(),
                'value' => $request->value,
            ]);
        }

    }

    /**
     * Valide le vote (admin uniquement).
     */
    public function validateVote(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Logique pour valider le vote (exemple : mettre à jour le statut de la réflexion)
        return response()->json(['message' => 'Vote validé avec succès.']);
    }
}
