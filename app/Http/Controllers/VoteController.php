<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Reflection;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    /**
     * Retourne les options de vote et le vote existant.
     */
    public function index($reflectionId)
    {
        $options = [
            ['id' => 1, 'option' => 'POUR'],
            ['id' => 2, 'option' => 'CONTRE'],
        ];
        
        $existingVote = Vote::where('reflection_id', $reflectionId)
        ->where('user_id', Auth::id())
        ->first();

        // dd($existingVote);
        return [
            'options' => $options,
            'existingVote' => $existingVote ? $existingVote->value : null,
        ];
    }

    /**
     * Enregistre ou met à jour un vote.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reflection_id' => 'required|exists:reflections,id',
            'value' => 'required|integer|in:1,-1',
        ]);

        $existingVote = Vote::where('reflection_id', $request->reflection_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            $existingVote->update(['value' => $request->value]);
        } else {
            Vote::create([
                'reflection_id' => $request->reflection_id,
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
        // dd($request->all());
        $request->validate([
            'reflection_id' => 'required|exists:reflections,id',
        ]);

        $reflection = Reflection::findOrFail($request->reflection_id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Logique pour valider le vote (exemple : mettre à jour le statut de la réflexion)
        $reflection->update(['statut' => 'valide']);

    }
}
