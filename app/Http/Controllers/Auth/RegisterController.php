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
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users',
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $data = $validator->validated();

            $user = User::create([
                'username' => $data['username'],
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
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
