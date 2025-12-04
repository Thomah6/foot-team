<?php

namespace App\Http\Controllers;

use App\Models\SuggestionComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsSuggestionController extends Controller
{
    public function update(Request $request, SuggestionComment $comment)
    {

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        $comment->update([
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Commentaire modifié !');
    }

    public function destroy(SuggestionComment $comment)
    {

        $comment->delete();
        return redirect()->back()->with('success', 'Commentaire supprimé !');
    }
}
