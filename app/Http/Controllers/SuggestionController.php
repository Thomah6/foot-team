<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::with(['user', 'comments.user', 'reactions'])
            ->latest()
            ->get();

        return Inertia::render('Suggestions/Index', [
            'suggestions' => $suggestions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $suggestion = Auth::user()->suggestions()->create($validated);

        return back()->with('success', 'Suggestion ajoutée avec succès.');
    }

    public function react(Suggestion $suggestion, Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        $reaction = $suggestion->reactions()
            ->updateOrCreate(
                ['user_id' => Auth::id()],
                ['type' => $validated['type']]
            );

        return response()->json([
            'success' => true,
            'reaction' => $reaction
        ]);
    }

    public function comment(Suggestion $suggestion, Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $suggestion->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content']
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }

    public function destroy(Suggestion $suggestion)
    {
        $this->authorize('delete', $suggestion);
        
        $suggestion->delete();
        
        return back()->with('success', 'Suggestion supprimée avec succès.');
    }
}
