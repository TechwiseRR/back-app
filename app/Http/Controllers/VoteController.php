<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;

class VoteController extends Controller
{
    /**
     * Afficher la liste des votes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Récupérer tous les votes avec leurs relations (user: id, username)
        $votes = Vote::with(['ressource', 'user:id,username'])->get();

        return response()->json($votes);
    }

    /**
     * Afficher les détails d'un vote spécifique
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vote = Vote::with(['ressource', 'user:id,username'])->find($id);

        // Vérifier si le vote existe
        if (!$vote) {
            return response()->json(['message' => 'Vote non trouvé'], 404);
        }

        return response()->json($vote);
    }

    /**
     * Ajouter un nouveau vote
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        // Valider les données entrantes
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'ressource_id' => 'required|integer|exists:ressources,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Créer un nouveau vote
        $vote = Vote::create($validated);

        return response()->json(['message' => 'Vote créé avec succès', 'vote' => $vote], 201);
    }

    /**
     * Mettre à jour un vote existant
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $vote = Vote::find($id);

        if (!$vote) {
            return response()->json(['message' => 'Vote non trouvé'], 404);
        }

        // Valider les données entrantes
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'ressource_id' => 'required|integer|exists:ressources,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Mettre à jour le vote
        $vote->update($validated);

        return response()->json(['message' => 'Vote mis à jour avec succès', 'vote' => $vote]);
    }

    /**
     * Supprimer un vote
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        $vote = Vote::find($id);

        if (!$vote) {
            return response()->json(['message' => 'Vote non trouvé'], 404);
        }

        // Supprimer le vote
        $vote->delete();

        return response()->json(['message' => 'Vote supprimé avec succès']);
    }
}
