<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Commentlike;
use App\Http\Requests\StoreCommentlikeRequest;
use App\Http\Requests\UpdateCommentlikeRequest;
use Illuminate\Support\Facades\Auth;

class CommentlikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function like(Comment $comment){
        $commentlike = CommentLike::create([
            "user_id"=> Auth::user()->id,
            "comment_id"=>$comment->id,
        ]);
        if ($commentlike) {
            return back();
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function dislike(Comment $comment)
    {
        $commentlike = CommentLike::where("user_id", Auth::user()->id)->where("comment_id", $comment->id)->first();
        $dislik= $commentlike->delete();
        if($dislik){
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentlikeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Commentlike $commentlike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commentlike $commentlike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentlikeRequest $request, Commentlike $commentlike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commentlike $commentlike)
    {
        //
    }
}
