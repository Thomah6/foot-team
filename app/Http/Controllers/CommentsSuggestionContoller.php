<?php

namespace App\Http\Controllers;

use App\Models\SuggestionComment;
use Illuminate\Http\Request;

class CommentsSuggestionContoller extends Controller
{
    public function update(Request $request, SuggestionComment $comment)
{
    if ($comment->user_id !== auth()->id()) {
        abort(403, "Vous ne pouvez pas modifier ce commentaire.");
    }

    $request->validate(['content' => 'required|string']);

    $comment->update(['content' => $request->content]);

    return back();
}
public function destroy(SuggestionComment $comment)
{
    if ($comment->user_id !== auth()->id()) {
        abort(403, "Vous ne pouvez pas supprimer ce commentaire.");
    }

    $comment->delete();

    return back();
}

}
