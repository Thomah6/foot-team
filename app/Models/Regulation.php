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
        'content',
        'parent_id',
        'order',
    ];

    /**
     * Relation vers le parent (ex: Article 1 → parent_id = null,
     * Article 1.1 → parent_id = id de Article 1).
     */
    public function parent()
    {
        return $this->belongsTo(Regulation::class, 'parent_id');
    }

        /**
     * Relation vers les enfants (ex: Article 1 → enfants = [1.1, 1.2]).
     */
    public function children()
    {
        return $this->hasMany(Regulation::class, 'parent_id')->orderBy('order');
    }
}