<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerOfTheMonth extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'month', 'reason', 'is_active', 'image'];

    protected $casts = [
        'month' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}