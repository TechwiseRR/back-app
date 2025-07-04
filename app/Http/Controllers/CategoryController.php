<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Affiche une liste de toutes les catégories.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Affiche les détails d'une catégorie spécifique.
     *
     * @param int $id
     */
    public function show($id)
    {
        $user = auth()->user();
        if (!$user || $user->roleId !== 1) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    /**
     * Ajoute une nouvelle catégorie.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function add(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->roleId !== 1) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category = Category::create($validatedData);
        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    /**
     * Met à jour une catégorie existante.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || $user->roleId !== 1) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($validatedData);
        return response()->json(['message' => 'Category updated successfully', 'data' => $category]);
    }

    /**
     * Supprime une catégorie existante.
     *
     * @param int $id
     */
    public function remove($id)
    {
        $user = auth()->user();
        if (!$user || $user->roleId !== 1) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        // Vérifier qu'aucune ressource n'est liée à cette catégorie
        if ($category->ressources()->count() > 0) {
            return response()->json(['message' => 'Impossible de supprimer : des ressources sont liées à cette catégorie.'], 409);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
