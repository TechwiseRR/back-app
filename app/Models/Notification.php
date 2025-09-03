<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
        'type',
        'user_id',
        'creation_date',
        'is_read',
    ];

    protected $casts = [
        'creation_date' => 'datetime',
        'is_read' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Méthode fictive pour l'envoi de notification
     */
    public function sendNotification(): void
    {
        // Implémentation à définir selon logique métier (email, socket, etc.)
    }
}
