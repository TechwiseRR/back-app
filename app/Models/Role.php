<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'roleName',
        'rank',
        'permission_id',
    ];

    /**
     * Relation avec la permission.
     * Un rôle possède une permission.
     */
    public function perm()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
