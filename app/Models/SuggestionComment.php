<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuggestionComment extends Model
{
    protected $fillable = [
        'user_id',
        'content',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class);
    }
    public function reation()
    {
        return $this->belongsTo(SuggestionReaction::class);
    }
}
