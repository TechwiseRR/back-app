<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    /**
     * Affiche une liste paginée de ressources avec filtrage et tri.
     * 
     * FONCTIONNALITÉS COUVERTES :
     * 1. Lister les ressources (par défaut : published uniquement)
     * 2. Lister les ressources restreintes (is_validated = false)
     * 3. Filtrer et trier les ressources (par catégorie, type, date, etc.)
     * 4. Recherche textuelle dans titre et description
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Ressource::with(['category', 'user', 'type']);

        // ============================================================================
        // FONCTIONNALITÉ 2 : LISTER LES RESSOURCES RESTREINTES
        // Usage: GET /api/ressources?restricted=true
        // Affiche les ressources avec is_validated = false (non validées)
        // ============================================================================
        if ($request->filled('restricted') && $request->boolean('restricted')) {
            $query->where('is_validated', false);
        }

        // ============================================================================
        // FILTRES DE VALIDATION
        // Usage: GET /api/ressources?is_validated=true
        // Permet de filtrer par statut de validation (true/false)
        // ============================================================================
        if ($request->filled('is_validated')) {
            $query->where('is_validated', $request->boolean('is_validated'));
        }

        // ============================================================================
        // FONCTIONNALITÉ 3 : FILTRER PAR CATÉGORIE
        // Usage: GET /api/ressources?category_id=1
        // Filtre les ressources par catégorie spécifique
        // ============================================================================
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // ============================================================================
        // FONCTIONNALITÉ 1 : STATUT DES RESSOURCES (LISTER LES RESSOURCES)
        // Par défaut : seules les ressources 'published' sont affichées
        // Usage: GET /api/ressources?status=draft (pour voir les brouillons)
        // ============================================================================
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, afficher seulement les ressources publiées
            $query->where('status', 'published');
        }

        // ============================================================================
        // FONCTIONNALITÉ 3 : FILTRER PAR TYPE DE RESSOURCE
        // Usage: GET /api/ressources?type_ressource_id=1
        // Filtre par type de ressource (vidéo, article, podcast, etc.)
        // ============================================================================
        if ($request->filled('type_ressource_id')) {
            $query->where('type_ressource_id', $request->type_ressource_id);
        }

        // ============================================================================
        // FONCTIONNALITÉ 3 : FILTRER PAR AUTEUR
        // Usage: GET /api/ressources?user_id=1
        // Affiche les ressources d'un utilisateur spécifique
        // ============================================================================
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // ============================================================================
        // FONCTIONNALITÉ 3 : RECHERCHE TEXTUELLE
        // Usage: GET /api/ressources?search=javascript
        // Recherche dans le titre ET la description des ressources
        // ============================================================================
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // ============================================================================
        // FONCTIONNALITÉ 3 : FILTRER PAR TAGS
        // Usage: GET /api/ressources?tag=tutorial
        // Filtre par tag spécifique dans le champ JSON tags
        // ============================================================================
        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        // ============================================================================
        // FONCTIONNALITÉ 3 : FILTRER PAR PLAGE DE DATES
        // Usage: GET /api/ressources?date_from=2025-01-01&date_to=2025-12-31
        // Filtre par date de publication
        // ============================================================================
        if ($request->filled('date_from')) {
            $query->where('publication_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('publication_date', '<=', $request->date_to);
        }

        // ============================================================================
        // FONCTIONNALITÉ 3 : TRI DES RESSOURCES
        // Usage: GET /api/ressources?sort_by=title&sort_order=asc
        // Tri par différents champs avec ordre ascendant/descendant
        // ============================================================================
        $sortBy = $request->get('sort_by', 'publication_date');
        $sortOrder = $request->get('sort_order', 'desc');

        // Champs de tri autorisés pour la sécurité
        $allowedSortFields = [
            'title',            // Tri alphabétique par titre
            'publication_date', // Tri par date de publication (défaut)
            'upvotes',         // Tri par nombre de votes positifs
            'downvotes',       // Tri par nombre de votes négatifs
            'created_at',      // Tri par date de création
            'validation_date', // Tri par date de validation
            'status'           // Tri par statut
        ];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Tri par défaut : publication_date desc (plus récent en premier)
            $query->orderBy('publication_date', 'desc');
        }

        // ============================================================================
        // PAGINATION
        // Usage: GET /api/ressources?per_page=20
        // Limite le nombre de résultats par page (max 50 pour les performances)
        // ============================================================================
        $perPage = min($request->get('per_page', 10), 50);
        $ressources = $query->paginate($perPage);

        // ============================================================================
        // RÉPONSE DYNAMIQUE SELON LES FILTRES APPLIQUÉS
        // ============================================================================
        $message = 'Liste des ressources récupérée avec succès';
        if ($request->filled('restricted') && $request->boolean('restricted')) {
            $message = 'Liste des ressources restreintes récupérée avec succès';
        }

        return response()->json([
            'message' => $message,
            'data' => $ressources->items(),
            'pagination' => [
                'current_page' => $ressources->currentPage(),
                'last_page' => $ressources->lastPage(),
                'per_page' => $ressources->perPage(),
                'total' => $ressources->total(),
                'from' => $ressources->firstItem(),
                'to' => $ressources->lastItem(),
            ],
            // Informations sur les filtres appliqués (utile pour le debug)
            'filters_applied' => [
                'restricted' => $request->boolean('restricted'),
                'status' => $request->get('status', 'published'),
                'category_id' => $request->get('category_id'),
                'type_ressource_id' => $request->get('type_ressource_id'),
                'search' => $request->get('search'),
                'tag' => $request->get('tag'),
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ]
        ]);
    }

    /**
     * Affiche les détails d'une ressource spécifique.
     *
     * @param  \App\Models\Ressource  $ressource
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Ressource $ressource)
    {
        // Charger les relations
        $ressource->load(['category', 'user', 'validator', 'type']);

        // Vérifier si la ressource est publiée
        if ($ressource->status !== 'published') {
            return response()->json([
                'error' => 'Cette ressource n\'est pas disponible'
            ], 404);
        }

        return response()->json([
            'message' => 'Ressource récupérée avec succès',
            'data' => $ressource
        ]);
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
            'description' => 'required|string',
            'content' => 'required|string',
            'url' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'validation_date' => 'nullable|date',
            'is_validated' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'upvotes' => 'nullable|integer|min:0',
            'downvotes' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'validator_id' => 'nullable|exists:users,id',
            'type_ressource_id' => 'required|exists:type_ressources,id',
        ]);

        $ressource = Ressource::create($validated);
        $ressource->load(['category', 'user', 'type']);

        return response()->json([
            'message' => 'Ressource créée avec succès',
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
            'description' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'url' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'status' => 'sometimes|required|in:draft,published,archived',
            'validation_date' => 'nullable|date',
            'is_validated' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'upvotes' => 'nullable|integer|min:0',
            'downvotes' => 'nullable|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'validator_id' => 'nullable|exists:users,id',
            'type_ressource_id' => 'sometimes|required|exists:type_ressources,id',
        ]);

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
        $ressource->delete();

        return response()->json([
            'message' => 'Ressource supprimée avec succès.'
        ], 200);
    }
}
