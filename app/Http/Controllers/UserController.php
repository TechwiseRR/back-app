<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Renvoie l'utilisateur connecté
     */
    public function user()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        return response()->json($user->makeHidden(['password']));
    }

    /**
     * Met à jour l'utilisateur connecté
     */
    public function updateSelf(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $data = $request->validate([
            'username'   => 'sometimes|required|string|max:50|unique:users,username,' . $user->id,
            'email'      => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password'   => 'sometimes|required|string|min:8|confirmed',
            'bio'        => 'nullable|string|max:500',
            'avatar'     => 'nullable|string|max:255',
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil mis à jour',
            'data' => $user->makeHidden(['password']),
        ]);
    }

    /**
     * Liste tous les utilisateurs (admin uniquement)
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user || $user->roleId !== 1) {
            return response()->json(['error' => 'Accès interdit'], 403);
        }

        return response()->json([
            'message' => 'Liste des utilisateurs',
            'data' => User::all()->makeHidden(['password']),
        ]);
    }

    /**
     * Affiche un utilisateur spécifique (admin uniquement)
     */
    public function show(User $user)
    {
        $authUser = auth()->user();

        if (!$authUser || $authUser->roleId !== 1) {
            return response()->json(['error' => 'Accès interdit'], 403);
        }

        return response()->json([
            'message' => 'Détails de l\'utilisateur',
            'data' => $user->makeHidden(['password']),
        ]);
    }

    /**
     * Met à jour un utilisateur (admin uniquement)
     */
    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        if (!$authUser || $authUser->roleId !== 1) {
            return response()->json(['error' => 'Accès interdit'], 403);
        }

        $data = $request->validate([
            'username'   => 'sometimes|required|string|max:50|unique:users,username,' . $user->id,
            'email'      => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password'   => 'sometimes|required|string|min:8|confirmed',
            'bio'        => 'nullable|string|max:500',
            'avatar'     => 'nullable|string|max:255',
            'isEmailVerified' => 'sometimes|boolean'
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Utilisateur mis à jour',
            'data' => $user->makeHidden(['password']),
        ]);
    }

    /**
     * Supprime un utilisateur (admin uniquement)
     */
    public function destroy(User $user)
    {
        $authUser = auth()->user();

        if (!$authUser || $authUser->roleId !== 1) {
            return response()->json(['error' => 'Accès interdit'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}
