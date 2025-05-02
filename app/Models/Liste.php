<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liste extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'user_id',
        'resource_id',
    ];

    protected $dates = [
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        // Si votre modèle ressource s’appelle "Ressource" :
        return $this->belongsTo(Ressource::class, 'resource_id');
    }
}
