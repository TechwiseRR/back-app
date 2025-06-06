<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    /**
     * Affiche les détails d'une ressource spécifique.
     *
     * @param  \App\Models\Ressource  $ressource
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Ressource $ressource)
    {
        return response()->json($ressource);
    }

    /**
     * Crée une nouvelle ressource et la sauvegarde dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publicationDate' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'validationDate' => 'nullable|date',
            'upvotes' => 'nullable|integer|min:0',
            'downvotes' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:users,id',
            'validator_id' => 'nullable|exists:users,id',
        ]);

        // Création de la ressource
        $ressource = Ressource::create($validated);

        return response()->json($ressource, 201); // Code HTTP 201 : Création
    }

    /**
     * Met à jour une ressource existante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ressource  $ressource
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Ressource $ressource)
    {
        // Validation des données
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'publicationDate' => 'nullable|date',
            'status' => 'sometimes|required|in:draft,published',
            'validationDate' => 'nullable|date',
            'upvotes' => 'nullable|integer|min:0',
            'downvotes' => 'nullable|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'author_id' => 'sometimes|required|exists:users,id',
            'validator_id' => 'nullable|exists:users,id',
        ]);

        // Mise à jour de la ressource
        $ressource->update($validated);

        return response()->json($ressource);
    }

    public function destroy(Ressource $ressource)
    {
        $ressource->delete();

        return response()->json([
            'message' => 'Ressource supprimée avec succès.'
        ], 200); // Code HTTP 200 : Succès

    }

}
