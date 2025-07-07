<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'creation_date',
        'ressource_id',
        'user_id',
    ];

    public function ressource() // Changé de resource() à ressource()
    {
        return $this->belongsTo(Ressource::class); // Changé de Resource à Ressource
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** Méthode logique du diagramme */
    public function postComment()
    {
        $this->creation_date = now();
        $this->save();
    }
}
