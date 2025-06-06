<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json(['error' => 'Aucun token fourni'], 400);
            }

            JWTAuth::invalidate($token);

            return response()->json([
                'message' => 'Déconnexion réussie'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la déconnexion',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
