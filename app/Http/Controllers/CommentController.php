<?php

namespace App\Http\Controllers;

use App\Models\Comment;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Affiche une liste de tous les commentaires.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }

    /**
     * Affiche un commentaire spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Commentaire introuvable'], 404);
        }

        return response()->json($comment);
    }

    /**
     * Ajoute un nouveau commentaire.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'resource_id' => 'required|exists:ressources,id',
        ]);

        $validatedData['user_id'] = auth()->id();

        $validatedData['ressource_id'] = $validatedData['resource_id'];
        unset($validatedData['resource_id']);

        $comment = Comment::create($validatedData);

        return response()->json(['message' => 'Commentaire ajouté avec succès', 'comment' => $comment], 201);
    }

    /**
     * Met à jour un commentaire existant.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Commentaire introuvable'], 404);
        }

        $validatedData = $request->validate([
            'content' => 'sometimes|string',
            'resource_id' => 'sometimes|exists:ressources,id',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $comment->update($validatedData);

        return response()->json(['message' => 'Commentaire mis à jour avec succès', 'comment' => $comment]);
    }

    /**
     * Supprime un commentaire.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Commentaire introuvable'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }

    /**
     * Répond à un commentaire.
     *
     */
    public function reply(Request $request, $id)
    {
        $parent = Comment::find($id);

        if (!$parent) {
            return response()->json(['message' => 'Commentaire parent introuvable'], 404);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $reply = Comment::create([
            'content'      => $validated['content'],
            'user_id'      => auth()->id(),
            'ressource_id' => $parent->ressource_id,
            'parent_id'    => $parent->id,
        ]);

        return response()->json(['message' => 'Réponse ajoutée', 'comment' => $reply], 201);
    }

    /**
     * Modère un commentaire (pour les modérateurs).
     *

     */
    public function moderate(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->role || $user->role->roleName !== 'Modérateur') {
            return response()->json(['error' => 'Accès interdit'], 403);
        }

        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Commentaire introuvable'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:visible,masqué,supprimé'
        ]);

        $comment->status = $validated['status'];
        $comment->save();

        return response()->json(['message' => 'Commentaire modéré', 'comment' => $comment]);
    }



}
