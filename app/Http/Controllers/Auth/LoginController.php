<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $credentials = $validator->validated();

            // Vérifier si l'utilisateur existe et est actif
            $user = \App\Models\User::where('email', $credentials['email'])->first();
            
            if (!$user) {
                return response()->json(['error' => 'Identifiants invalides'], 401);
            }
            
            if (!$user->isActive()) {
                return response()->json([
                    'error' => 'Compte désactivé',
                    'message' => 'Ce compte a été désactivé. Contactez un administrateur pour le réactiver.'
                ], 403);
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Identifiants invalides'], 401);
            }

            return response()->json([
                'message' => 'Connexion réussie',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => JWTFactory::getTTL() * 60,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la connexion',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
