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

        return response()->json([
            'success' => true,
            'comment' => $comment->fresh()
        ]);
    }

    public function destroy(SuggestionComment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé avec succès.'
        ]);
    }
}
