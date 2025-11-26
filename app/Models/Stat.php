<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'goals', 
        'assists', 
        'goals_against', 
        'matches_played', 
        'date', 
        'validated_by_admin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}