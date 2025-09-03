<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use App\Models\RessourceValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RessourceValidationController extends Controller
{
    /**
     * Affiche la liste des ressources en attente de validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Vérifier que l'utilisateur est modérateur ou admin
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isModerator()) {
            return response()->json([
                'error' => 'Accès non autorisé. Seuls les modérateurs et administrateurs peuvent accéder à cette fonctionnalité.'
            ], 403);
        }

        $query = Ressource::with(['category', 'user', 'type'])
            ->where('is_validated', false)
            ->where('status', '!=', 'archived');

        // Filtrage par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
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
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = ['title', 'created_at', 'publication_date'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = min($request->get('per_page', 10), 50);
        $ressources = $query->paginate($perPage);

        return response()->json([
            'message' => 'Liste des ressources en attente de validation récupérée avec succès',
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
     * Valide ou rejette une ressource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ressource  $ressource
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request, Ressource $ressource)
    {
        // Vérifier que l'utilisateur est modérateur ou admin
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isModerator()) {
            return response()->json([
                'error' => 'Accès non autorisé. Seuls les modérateurs et administrateurs peuvent valider les ressources.'
            ], 403);
        }

        // Vérifier que la ressource n'est pas déjà validée
        if ($ressource->is_validated) {
            return response()->json([
                'error' => 'Cette ressource est déjà validée.'
            ], 400);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Convertir l'action en status pour la base de données
        $status = $validated['action'] === 'approve' ? 'approved' : 'rejected';

        try {
            DB::beginTransaction();

            // Préparer les données de mise à jour de la ressource
            $ressourceData = [
                'is_validated' => $validated['action'] === 'approve',
                'validation_date' => now(),
                'validator_id' => $user->id,
            ];

            // Si la ressource est rejetée, la passer en statut "archived"
            if ($validated['action'] === 'reject') {
                $ressourceData['status'] = 'archived';
            }

            // Mettre à jour la ressource
            $ressource->update($ressourceData);

            // Créer un enregistrement de validation
            RessourceValidation::create([
                'ressource_id' => $ressource->id,
                'validator_id' => $user->id,
                'status' => $status,
                'comment' => $validated['comment'] ?? null,
                'validation_date' => now(),
            ]);

            DB::commit();

            $actionText = $validated['action'] === 'approve' ? 'validée' : 'rejetée';
            
            return response()->json([
                'message' => "Ressource {$actionText} avec succès",
                'data' => $ressource->load(['category', 'user', 'type', 'validator'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur validation ressource: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Erreur lors de la validation de la ressource: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche l'historique des validations pour une ressource.
     *
     * @param  \App\Models\Ressource  $ressource
     * @return \Illuminate\Http\JsonResponse
     */
    public function history(Ressource $ressource)
    {
        // Vérifier que l'utilisateur est modérateur ou admin
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isModerator()) {
            return response()->json([
                'error' => 'Accès non autorisé. Seuls les modérateurs et administrateurs peuvent accéder à l\'historique.'
            ], 403);
        }

        $validations = RessourceValidation::with('validator')
            ->where('ressource_id', $ressource->id)
            ->orderBy('validation_date', 'desc')
            ->get();

        return response()->json([
            'message' => 'Historique des validations récupéré avec succès',
            'data' => $validations
        ]);
    }

}
