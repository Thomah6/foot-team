<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
    public function like(Comment $comment)
    {
        $userId = Auth::id();

        // Vérifier si l'utilisateur a déjà une interaction
        $interaction = CommentLike::where('user_id', $userId)
                                  ->where('comment_id', $comment->id)
                                  ->first();

        if (!$interaction) {
            // Aucun like/dislike → créer un like
            CommentLike::create([
                'user_id' => $userId,
                'comment_id' => $comment->id,
                'like' => 1,
            ]);
        } else {
            // Si déjà LIKE → remettre à NULL (retirer like)
            if ($interaction->like === 1) {
                $interaction->update(['like' => null]);
            }

            // Si DISLIKE → changer en LIKE
            elseif ($interaction->like === -1) {
                $interaction->update(['like' => 1]);
            }

            // Si NULL → mettre LIKE
            else {
                $interaction->update(['like' => 1]);
            }
        }

        return back();
    }

    public function dislike(Comment $comment)
    {
        $userId = Auth::id();

        $interaction = CommentLike::where('user_id', $userId)
                                  ->where('comment_id', $comment->id)
                                  ->first();

        if (!$interaction) {
            // Aucun like/dislike → créer un dislike
            CommentLike::create([
                'user_id' => $userId,
                'comment_id' => $comment->id,
                'like' => -1,
            ]);
        } else {
            // Si déjà DISLIKE → remettre à NULL (retirer dislike)
            if ($interaction->like === -1) {
                $interaction->update(['like' => null]);
            }

            // Si LIKE → changer en DISLIKE
            elseif ($interaction->like === 1) {
                $interaction->update(['like' => -1]);
            }

            // Si NULL → mettre DISLIKE
            else {
                $interaction->update(['like' => -1]);
            }
        }

        return back();
    }
}
