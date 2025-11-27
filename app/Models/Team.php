<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'color'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    public function stats(){
        return $this->hasMany(TeamStat::class);
    }

    //Fonction pour obtenir les stats du mois courant
    public function currentMonthStats(){
        $currentMonth = now()->startOfMonth();
        return $this->stats()->where('month',$currentMonth->toDateString())->first();
    }

    //Fonction pour récupérer les stats du dernier mois 
    public function lastMonthStat(){
        return $this->stats()->orderBy('month','desc')->first();
    }

}