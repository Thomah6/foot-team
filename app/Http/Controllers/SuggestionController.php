<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Models\SuggestionComment;
use App\Models\SuggestionReaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuggestionController extends Controller
{

    public function index()
    {
        return Inertia::render("SuggestionPage", [
            'suggestions' => Suggestion::with([
                'user',
                'comments.user',
                'reactions'
            ])->latest()->get(),
            'authUser' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'content' => 'required|min:5'
        ]);

        Suggestion::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back();
    }

    //Pour les commentaires
    public function update(Request $request, SuggestionComment $suggestionComment){
        if ($suggestionComment->user_id !== auth()->id()) {
            abort(403, "Vous ne pouvez pas modifier ce commentaire.");
        }
        $suggestionComment->update([
            'content' => $request->content
        ]);

        return back();
    }


    public function react(Suggestion $suggestion, Request $request)
    {

        $request->validate(['type' => 'required|in:like,dislike']);

        $userId = auth()->id();

        $existing = SuggestionReaction::where([
            'suggestion_id' => $suggestion->id,
            'user_id' => $userId,
        ])->first();

        // ✔ 1 — Si la même réaction → supprimer
        if ($existing && $existing->type === $request->type) {
            $existing->delete();
            return back();
        }

        // ✔ 2 — Si l'user a une réaction mais d’un type différent → switch
        if ($existing) {
            $existing->update(['type' => $request->type]);
            return back();
        }
        // dd($existing);
        SuggestionReaction::updateOrCreate([
            'suggestion_id' => $suggestion->id,
             'user_id' => auth()->id(),
            'type' => $request->type,
        ]);
        return back();
    }

    // ✔ 3 — Pas de réaction → créer
    public function comment(Suggestion $suggestion, Request $request)
    {
        $request->validate(['content' => 'required']);

        $suggestion->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back();
    }

    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->back()->with('success', 'Suggestion supprimé !');
    }
}
