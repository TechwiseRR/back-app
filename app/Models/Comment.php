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
        'resource_id',
        'author_id',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
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
