<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegulationContent extends Model
{
    protected $fillable = ['regulation_id', 'sub_number', 'content', 'order'];

    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }
}
