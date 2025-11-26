<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'pseudo',
        'email',
        'password',
        'avatar',
        'poster',
        'role',
        'position',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relations

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    public function reflections()
    {
        return $this->hasMany(Reflection::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function stats()
    {
        return $this->hasMany(Stat::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function finances()
    {
        return $this->hasMany(Finance::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function galleryLikes()
    {
        return $this->hasMany(GalleryLike::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function playerOfTheMonths()
    {
        return $this->hasMany(PlayerOfTheMonth::class);
    }
}
