<?php

namespace App\Http\Controllers;

use App\Models\InfoProfile;
use Illuminate\Http\Request;

class InfoProfileController extends Controller
{
    /**
     * Affiche tous les profils avec leurs utilisateurs associés
     */
    public function index()
    {
        return response()->json(InfoProfile::with('user')->get());
    }

    /**
     * Crée un nouveau profil
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
            'postal_code'  => 'nullable|string|max:20',
            'user_id'      => 'required|exists:users,id',
        ]);

        $infoProfile = InfoProfile::create($validated);

        return response()->json([
            'message' => 'Profil créé ',
            'data' => $infoProfile,
        ], 201);
    }

    /**
     * Affiche un profil en particulier
     */
    public function show(InfoProfile $infoProfile)
    {
        return response()->json($infoProfile->load('user'));
    }

    /**
     * Met à jour un profil
     */
    public function update(Request $request, InfoProfile $infoProfile)
    {
        $validated = $request->validate([
            'first_name'   => 'sometimes|required|string|max:255',
            'last_name'    => 'sometimes|required|string|max:255',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
            'postal_code'  => 'nullable|string|max:20',
        ]);

        $infoProfile->update($validated);

        return response()->json([
            'message' => 'Profil mis à jour ',
            'data' => $infoProfile,
        ]);
    }


    public function destroy(InfoProfile $infoProfile)
    {
        $infoProfile->delete();
        return response()->noContent();
    }
}
