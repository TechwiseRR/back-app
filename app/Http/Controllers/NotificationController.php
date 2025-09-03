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
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'user_id' => 'required|exists:users,id',
            'creation_date' => 'nullable|date',
            'is_read' => 'boolean',
        ]);

        // Définir la date de création à maintenant si elle n'est pas fournie
        if (!isset($validated['creation_date'])) {
            $validated['creation_date'] = now();
        }

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
            'title' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:info,warning,success,error',
            'creation_date' => 'nullable|date',
            'is_read' => 'boolean',
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
            'message' => 'Notification supprimée avec succès'
        ], 200);
    }

}
