<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comments(){
        return $this->hasMany(SuggestionComment::class);
    }

    public function reactions(){
        return $this->hasMany(SuggestionReaction::class);
    }
}
