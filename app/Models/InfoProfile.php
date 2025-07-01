<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoProfile extends Model
{
    use HasFactory;

    protected $table = 'info_profiles';

    protected $fillable = [
        'userId',
        'firstName',
        'lastName',
        'address',
        'postalCode',
        'city',
        'country',
        'updateDate',
    ];

    protected $casts = [
        'updateDate' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
