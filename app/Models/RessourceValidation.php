<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'moderator_id',
        'validationStatus',
        'validationDate',
        'comment',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

}
