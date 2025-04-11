<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'user_id',
        'voteType',
        'voteDate',
    ];


    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function castVote()
    {
        $this->voteDate = now();
        $this->save();
    }
}
