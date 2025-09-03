<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RessourceValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ressource_id',
        'validator_id',
        'status',
        'comment',
        'validation_date',
    ];

    public function ressource()
    {
        return $this->belongsTo(Ressource::class, 'ressource_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

}
