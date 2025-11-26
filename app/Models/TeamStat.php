<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class TeamStat extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'wins', 'losses', 'draws', 'month'];

    public function team() {
        return $this->belongsTo(Team::class);
    }
}
