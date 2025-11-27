<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Vote;
use App\Models\Comment;

class Reflection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'titre', 'contenu', 'statut', 'date_fin_vote'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
