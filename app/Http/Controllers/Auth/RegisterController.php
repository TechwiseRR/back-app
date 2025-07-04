<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'registrationDate' => now(),
                'updateDate' => now(),
                'isEmailVerified' => false,
                'roleId' => 1,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Inscription réussie',
                'user' => $user->makeHidden(['password']),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de l\'inscription',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
