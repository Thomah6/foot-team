<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['reflection_id', 'user_id', 'value'];

    public function reflection()
    {
        return $this->belongsTo(Reflection::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}