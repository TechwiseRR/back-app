<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'registrationDate',
        'updateDate',
        'isEmailVerified',
        'is_active',
        'roleId',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'rememberToken',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registrationDate' => 'datetime',
        'isEmailVerified' => 'boolean',
        'password' => 'hashed',
    ];

    public function role()
    {

        return $this->belongsTo(Role::class, 'roleId');
    }

    // Implémentation des méthodes requises par JWTSubject
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Vérifie si l'utilisateur est un administrateur.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role && $this->role->rank === 1;
    }

    /**
     * Vérifie si l'utilisateur est un modérateur.
     *
     * @return bool
     */
    public function isModerator()
    {
        return $this->role && $this->role->rank === 2;
    }

    /**
     * Vérifie si l'utilisateur est actif.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active === true;
    }

    /**
     * Vérifie si l'utilisateur est anonyme.
     *
     * @return bool
     */
    public function isAnonymous()
    {
        return $this->email === 'anonymous@domain.com';
    }
}
