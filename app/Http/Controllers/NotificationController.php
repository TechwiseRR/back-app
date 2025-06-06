<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Liste des notifications',
            'data' => Notification::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'notificationType' => 'required|string',
            'content' => 'required|string',
            'userId' => 'required|exists:users,id',
            'notificationDate' => 'nullable|date',
            'read' => 'boolean',
        ]);

        $notification = Notification::create($validated);

        return response()->json([
            'message' => 'Notification créée',
            'data' => $notification,
        ], 201);
    }

    public function show(Notification $notification)
    {
        return response()->json([
            'message' => 'Détail de la notification',
            'data' => $notification->load('user'),
        ]);
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'notificationType' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'notificationDate' => 'nullable|date',
            'read' => 'boolean',
        ]);

        $notification->update($validated);

        return response()->json([
            'message' => 'Notification mise à jour',
            'data' => $notification,
        ]);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json([
            'message' => 'Notification supprimée'
        ], 204);
    }
}
