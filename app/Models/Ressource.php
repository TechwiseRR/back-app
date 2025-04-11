<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $table = 'ressource';
    protected $primaryKey = 'id_ressource';

    protected $fillable = [
        'lib_ressource',
        'desc_ressource',
        'id_type',
        'date_create',
        'visibility_ressource',
        'id_user',
        'id_cat',
        'id_rel',
    ];

    public function type()
    {
        return $this->belongsTo(TypeRessource::class, 'id_type');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_cat');
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class, 'id_rel');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function validations()
    {
        return $this->hasMany(RessourceValidation::class, 'id_ressource');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'id_ressource');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'id_ressource');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueAction::class, 'id_ressource');
    }

    public function listes()
    {
        return $this->belongsToMany(Liste::class, 'ajouter', 'id_ressource', 'id_liste');
    }

    public function statuts()
    {
        return $this->hasMany(StatutRessource::class, 'id_ressource');
    }
}

