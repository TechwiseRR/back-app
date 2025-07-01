<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'commentDate',
        'ressource_id', // Changé de resource_id à ressource_id
        'author_id',
    ];

    public function ressource() // Changé de resource() à ressource()
    {
        return $this->belongsTo(Ressource::class); // Changé de Resource à Ressource
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** Méthode logique du diagramme */
    public function postComment()
    {
        $this->commentDate = now();
        $this->save();
    }
}
