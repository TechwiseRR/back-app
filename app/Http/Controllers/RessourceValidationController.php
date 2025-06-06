<?php

namespace App\Http\Controllers;

use App\Models\ResourceValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RessourceValidationController extends Controller
{
    /**
     * Affiche une liste de toutes les ressources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $validations = ResourceValidation::all();
        return response()->json($validations, Response::HTTP_OK);
    }

    /**
     * Affiche une ressource spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $validation = ResourceValidation::find($id);

        if (!$validation) {
            return response()->json(['error' => 'Ressource non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($validation, Response::HTTP_OK);
    }

    /**
     * Crée une nouvelle ressource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'validationStatus' => 'required|string',
            'validationDate' => 'required|date',
            'comment' => 'nullable|string',
            'resource_id' => 'required|integer|exists:resources,id',
            'moderator_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validation = ResourceValidation::create($request->all());

        return response()->json($validation, Response::HTTP_CREATED);
    }

    /**
     * Met à jour une ressource existante.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = ResourceValidation::find($id);

        if (!$validation) {
            return response()->json(['error' => 'Ressource non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'validationStatus' => 'string',
            'validationDate' => 'date',
            'comment' => 'nullable|string',
            'resource_id' => 'integer|exists:resources,id',
            'moderator_id' => 'integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validation->update($request->all());

        return response()->json($validation, Response::HTTP_OK);
    }

    /**
     * Supprime une ressource existante.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $validation = ResourceValidation::find($id);

        if (!$validation) {
            return response()->json(['error' => 'Ressource non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $validation->delete();

        return response()->json(['message' => 'Ressource supprimée avec succès.'], Response::HTTP_OK);
    }
}
