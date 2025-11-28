<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuggestionReaction extends Model
{
    protected $fillable = [
        'suggestion_id',
        'user_id',
        'type'
    ];

    public function Suggestion(){
        return $this->belongsTo(Suggestion::class);
    }

    public function comment(){
        return $this->belongsTo(SuggestionComment::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
