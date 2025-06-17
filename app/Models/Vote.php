
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'voteType',
        'voteDate',
        'ressource_id', // Changé de resource_id à ressource_id
        'user_id',
    ];

    public function ressource() // Changé de resource() à ressource()
    {
        return $this->belongsTo(Ressource::class); // Changé de Resource à Ressource
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
