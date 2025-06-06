<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'userId',
        'notificationType',
        'content',
        'notificationDate',
        'read',
    ];

    protected $casts = [
        'notificationDate' => 'datetime',
        'read' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Méthode fictive pour l'envoi de notification
     */
    public function sendNotification(): void
    {
        // Implémentation à définir selon logique métier (email, socket, etc.)
    }

}
