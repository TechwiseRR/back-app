<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'url',
        'publication_date',
        'status',
        'validation_date',
        'is_validated',
        'tags',
        'upvotes',
        'downvotes',
        'category_id',
        'user_id',
        'validator_id',
        'type_ressource_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeRessource::class, 'type_ressource_id');
    }

    // Relations inverses ajoutées
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function historiqueActions()
    {
        return $this->hasMany(HistoriqueAction::class);
    }
}
