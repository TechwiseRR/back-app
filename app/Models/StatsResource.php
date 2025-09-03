<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatsResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'date',
        'count',
        'resource_id',
    ];

    protected $dates = [
        'date',
    ];

    public function resource()
    {
        return $this->belongsTo(Ressource::class, 'resource_id');
    }
}
