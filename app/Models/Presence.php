<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'present', 'validated_by_admin', 'declared_by_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function declaredByUser()
    {
        return $this->belongsTo(User::class, 'declared_by_user_id');
    }
}