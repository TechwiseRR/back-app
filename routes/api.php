<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InfoProfileController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;

// Authentification
Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
});


Route::middleware('auth:api')->group(function () {
// Profil personnel
    Route::get('/user', [UserController::class, 'user']);
    Route::put('/user', [UserController::class, 'updateSelf']);

// Gestion des utilisateurs (admin uniquement)
    Route::apiResource('users', UserController::class)->except(['store']);

// Déconnexion
    Route::post('/logout', [LogoutController::class, 'logout']);

// Notifications
    Route::apiResource('/notifications', NotificationController::class);

// Permissions
    Route::apiResource('/permissions', PermissionController::class);

// Informations de profil utilisateur
    Route::get('/info-profile', [InfoProfileController::class]);

// Ressources
    Route::apiResource('/resources', RessourceController::class);

// Routes pour Role
    Route::apiResource('/roles', RoleController::class);

// Routes pour Category
    Route::apiResource('/categories', CategoryController::class);

});
