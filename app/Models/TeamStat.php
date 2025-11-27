<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TeamStat extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'wins', 'losses', 'draws', 'month', 'validated'];

    protected $casts = [
        'month' => 'date',
        'wins' => 'integer',
        'losses' => 'integer',
        'draws' => 'integer',
        'validated' => 'boolean',
    ];

    //les relations 

    public function team() {
        return $this->belongsTo(Team::class);
    }

    // Accesseurs
    public function getTotalMatchesAttribute()
    {
        return (int) $this->wins + (int) $this->losses + (int) $this->draws;
    }

    public function getPointsAttribute()
    {
        return ($this->wins * 3) + $this->draws;
    }

    public function getWinRateAttribute()
    {
        $total = $this->getTotalMatchesAttribute();
        if ($total === 0) {
            return 0;
        }

        return round(($this->wins / $total) * 100, 2);
    }

    //SCOPES
    public function scopeByTeam($query,$teamId){
        return $query->where('team_id',$teamId);
    }

    public function scopeByMonth($query,$month){
        return $query->where('month',$month);
    }

    public function scopeByLatest($query){
        return $query->orderBy('month','desc');
    }
}
