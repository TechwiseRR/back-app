<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    /**
     * Affiche une liste paginée de ressources avec filtrage et tri.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Ressource::with(['category', 'user', 'type']);

        // Filtrage par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtrage par statut (publié uniquement pour les utilisateurs non connectés)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, afficher seulement les ressources publiées
            $query->where('status', 'published');
        }

        // Filtrage par type de ressource
        if ($request->filled('type_ressource_id')) {
            $query->where('type_ressource_id', $request->type_ressource_id);
        }

        // Filtrage par auteur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Tri
        $sortBy = $request->get('sort_by', 'publication_date');
        $sortOrder = $request->get('sort_order', 'desc');

        // Vérifier que les champs de tri sont autorisés
        $allowedSortFields = ['title', 'publication_date', 'upvotes', 'downvotes', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('publication_date', 'desc');
        }

        // Pagination
        $perPage = min($request->get('per_page', 10), 50);
        $ressources = $query->paginate($perPage);

        return response()->json([
            'message' => 'Liste des ressources récupérée avec succès',
            'data' => $ressources->items(),
            'pagination' => [
                'current_page' => $ressources->currentPage(),
                'last_page' => $ressources->lastPage(),
                'per_page' => $ressources->perPage(),
                'total' => $ressources->total(),
                'from' => $ressources->firstItem(),
                'to' => $ressources->lastItem(),
            ]
        ]);
    }

    /**
     * Affiche les détails d'une ressource spécifique.
     *
     * @param  \App\Models\Ressource  $ressource
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ressource = \App\Models\Ressource::with(['votes.user'])->find($id);

        if (!$ressource) {
            return response()->json(['message' => 'Ressource non trouvée'], 404);
        }

        // Transformer les votes pour ne garder que l'id et le username de l'utilisateur
        $votes = $ressource->votes->map(function($vote) {
            return [
                'id' => $vote->id,
                'type' => $vote->type,
                'ressource_id' => $vote->ressource_id,
                'user_id' => $vote->user_id,
                'created_at' => $vote->created_at,
                'updated_at' => $vote->updated_at,
                'user' => $vote->user ? [
                    'id' => $vote->user->id,
                    'username' => $vote->user->username,
                ] : null,
            ];
        });

        // Retourner la ressource avec les votes transformés
        $data = $ressource->toArray();
        $data['votes'] = $votes;

        return response()->json($data);
    }

    /**
     * Crée une nouvelle ressource et la sauvegarde dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'required|string',
            'publication_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'validation_date' => 'nullable|date',
            'upvotes' => 'nullable|integer|min:0',
            'downvotes' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'validator_id' => 'nullable|exists:users,id',
            'type_ressource_id' => 'required|exists:type_ressources,id',
        ]);

        // Récupérer l'utilisateur connecté
        $user = auth()->user();
        
        // Déterminer si l'utilisateur est admin
        $isAdmin = $user->isAdmin();
        
        // Créer la ressource avec les données validées
        $ressourceData = array_merge($validated, [
            'user_id' => $user->id,
            'is_validated' => $isAdmin, // true pour les admins, false pour les utilisateurs normaux
        ]);

        $ressource = Ressource::create($ressourceData);
        $ressource->load(['category', 'user', 'type']);

        $message = $isAdmin ? 'Ressource créée et validée avec succès' : 'Ressource créée avec succès (en attente de validation)';

        return response()->json([
            'message' => $message,
            'data' => $ressource
        ], 201);
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
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'publication_date' => 'nullable|date',
            'status' => 'sometimes|required|in:draft,published,archived',
            'validation_date' => 'nullable|date',
            'upvotes' => 'nullable|integer|min:0',
            'downvotes' => 'nullable|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'validator_id' => 'nullable|exists:users,id',
            'type_ressource_id' => 'sometimes|required|exists:type_ressources,id',
        ]);

        // Récupérer l'utilisateur connecté
        $user = auth()->user();
        
        // Vérifier que l'utilisateur peut modifier cette ressource
        if ($ressource->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à modifier cette ressource'
            ], 403);
        }

        // Seuls les admins peuvent modifier le champ is_validated
        if (isset($validated['is_validated']) && !$user->isAdmin()) {
            unset($validated['is_validated']);
        }

        $ressource->update($validated);
        $ressource->load(['category', 'user', 'type']);

        return response()->json([
            'message' => 'Ressource mise à jour avec succès',
            'data' => $ressource
        ]);
    }

    /**
     * Supprime une ressource.
     *
     * @param  \App\Models\Ressource  $ressource
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Ressource $ressource)
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();
        
        // Vérifier que l'utilisateur peut supprimer cette ressource
        if ($ressource->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à supprimer cette ressource'
            ], 403);
        }

        $ressource->delete();

        return response()->json([
            'message' => 'Ressource supprimée avec succès.'
        ], 200);
    }
}
