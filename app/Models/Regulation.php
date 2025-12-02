<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;

    /**
     * Les champs autorisés en ...
     */
    protected $fillable = [
        'title',
    ];

    public function contents()
    {
        return $this->hasMany(RegulationContent::class)->orderBy('order');
    }

}