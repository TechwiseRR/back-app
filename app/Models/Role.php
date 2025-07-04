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
        'permissionId',
    ];

    /**
     * Relation avec la permission.
     * Un rôle possède une permission.
     */
    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'permissionId');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'roleId');
    }

    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $roleName;

    /**
     * @var int
     */
    private int $rank;

    /**
     * @var int
     */
    private int $permissionId;
}
