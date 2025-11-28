<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reflection;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

    public function history()
    {
        $votes = Vote::with('reflection')
            ->where('user_id', Auth::id())
            ->get(['id', 'reflection_id', 'value', 'created_at']);
            dd($votes);
            return Inertia::render('VoteHistory', [
                'votes' => $votes,
            ]);
    }

    public function listAdmin()
    {
        $reflections = Reflection::with(['votes' => function ($query) {
            $query->select('id', 'reflection_id', 'value', 'user_id')
                ->with('user:id,role');
        }])->get(['id', 'titre']);

        $data = $reflections->map(function ($reflection) {
            $votes = $reflection->votes;
            $pour = $votes->where('value', 1);
            $contre = $votes->where('value', -1);

            $pourCount = $pour->where('user.role', 'simple')->count() +
                $pour->whereIn('user.role', ['admin', 'bureau'])->count() * 2;

            $contreCount = $contre->where('user.role', 'simple')->count() +
                $contre->whereIn('user.role', ['admin', 'bureau'])->count() * 2;

            $total = $pourCount + $contreCount;

            return [
                'id' => $reflection->id,
                'title' => $reflection->title,
                'pourPercentage' => $total ? round(($pourCount / $total) * 100, 2) : 0,
                'contrePercentage' => $total ? round(($contreCount / $total) * 100, 2) : 0,
                'winner' => $pourCount > $contreCount ? 'POUR' : 'CONTRE',
                'votes' => $votes->map(function ($vote) {
                    return [
                        'id' => $vote->id,
                        'value' => $vote->value,
                        'is_bureau' => in_array($vote->user->role, ['admin', 'bureau']),
                    ];
                }),
            ];
        });
        return Inertia::render('VoteListAdmin', [
            'reflections' => $data,
        ]);
    
    }
}
