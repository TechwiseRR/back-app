<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueAction;

use Illuminate\Http\Request;

class HistoriqueActionController extends Controller
{
    /**
     * Afficher la liste des historiques d'actions.
     */
    public function index()
    {
        $actions = HistoriqueAction::with(['user', 'ressource'])->get(); // Inclut les relations user et ressource
        return response()->json($actions);
    }

    /**
     * Afficher un historique d'action spécifique.
     */
    public function show($id)
    {
        $action = HistoriqueAction::with(['user', 'ressource'])->find($id);

        if (!$action) {
            return response()->json(['message' => 'Historique non trouvé'], 404);
        }

        return response()->json($action);
    }

    /**
     * Ajouter un nouvel historique d'action.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'typeAction' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'resource_id' => 'nullable|exists:ressources,id', // Resource optionnel
        ]);

        $action = HistoriqueAction::create($validatedData);

        return response()->json([
            'message' => 'Historique créé avec succès',
            'action' => $action,
        ], 201);
    }

    /**
     * Mettre à jour un historique d'action existant.
     */
    public function update(Request $request, $id)
    {
        $action = HistoriqueAction::find($id);

        if (!$action) {
            return response()->json(['message' => 'Historique non trouvé'], 404);
        }

        $validatedData = $request->validate([
            'date' => 'sometimes|date',
            'typeAction' => 'sometimes|string|max:255',
            'message' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'resource_id' => 'sometimes|nullable|exists:ressources,id',
        ]);

        $action->update($validatedData);

        return response()->json([
            'message' => 'Historique mis à jour avec succès',
            'action' => $action,
        ]);
    }

    /**
     * Supprimer un historique d'action.
     */
    public function destroy($id)
    {
        $action = HistoriqueAction::find($id);

        if (!$action) {
            return response()->json(['message' => 'Historique non trouvé'], 404);
        }

        $action->delete();

        return response()->json(['message' => 'Historique supprimé avec succès']);
    }
}
