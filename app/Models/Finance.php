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

    protected $appends = ['user_name', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name ?? 'N/A';
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}