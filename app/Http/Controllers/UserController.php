<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function user()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        return response()->json(auth()->user()->makeHidden(['password']));
    }

}
