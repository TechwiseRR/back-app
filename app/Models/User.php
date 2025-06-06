<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'firstName',
        'lastName',
        'email',
        'password',
        'avatar',
        'bio',
        'registrationDate',
        'updateDate',
        'isEmailVerified',
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
        return $this->belongsTo(Role::class,'permissionId');
    }
}
