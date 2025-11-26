<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'montant', 'type', 'statut_valide', 'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}