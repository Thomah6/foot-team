<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    /**
     * Les colonnes qui peuvent être remplies automatiquement
     * lors de la création ou mise à jour d'un enrégistrement
     */
    protected $fillable = [
        'user_id',
        'image_path',
        'description'
    ];

    /**
     * Relation : une photo appartient à un utilisateur.
     * Cela permet d'accéder directement aux infos de l'uploader
     * via $gallery->user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(GalleryLike::class);
    }
}
