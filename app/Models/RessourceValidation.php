<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'validationStatus',
        'validationDate',
        'comment',
        'resource_id',
        'moderator_id',
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
