<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reflection  $reflection
     * @return \Illuminate\Http\RedirectResponse
     */

    static function index(Reflection $reflection){
        // dd($reflection->toArray());

        $comments = Comment::where("reflection_id",$reflection->id)->get();
        return $comments;
    }
     static function ravel(Reflection $reflection){
        // dd($reflection->toArray());

        $comments = Comment::with('user', 'reflection')->where("reflection_id",$reflection->id)->get();
        return $comments;
    }


    public function store(Request $request, Reflection $reflection)
    {
        // dd($request->toArray());
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);


        Comment::create([
            'reflection_id'=>$request->reflection_id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $comment->update($request->only('content'));

        return back()->with('success', 'Commentaire mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');
    }
}

