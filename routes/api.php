<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RessourceValidationController;

// Authentification
Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
});

// Routes publiques pour les catégories (accessible à tous)
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
});

// Routes publiques pour les ressources (accessibles à tous - même non connectés)
Route::prefix('ressources')->group(function () {
    Route::get('/', [RessourceController::class, 'index']); // Lister les ressources
    Route::get('/{ressource}', [RessourceController::class, 'show']); // Afficher une ressource
});

Route::middleware('auth:api')->group(function () {
// Profil personnel
    Route::get('/user', [UserController::class, 'user']);
    Route::put('/user', [UserController::class, 'updateSelf']);

// Gestion des ressources (utilisateurs connectés)
    Route::prefix('ressources')->group(function () {
        Route::post('/', [RessourceController::class, 'store']); // Créer une ressource
        Route::put('/{ressource}', [RessourceController::class, 'update']); // Modifier une ressource
        Route::delete('/{ressource}', [RessourceController::class, 'destroy']); // Supprimer une ressource
    });

// Gestion des utilisateurs (admin uniquement)
    Route::apiResource('users', UserController::class)->except(['store']);

// Déconnexion
    Route::post('/logout', [LogoutController::class, 'logout']);

// Notifications
    Route::apiResource('notifications', NotificationController::class);

// Permissions
    Route::apiResource('permissions', PermissionController::class);

// Routes protégées pour la gestion admin des catégories
    Route::prefix('categories')->group(function () {
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'add']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'remove']);
    });

// Routes pour la validation des ressources (modérateurs et admins)
    Route::prefix('validation')->group(function () {
        Route::get('/ressources', [RessourceValidationController::class, 'index']); // Liste des ressources à valider
        Route::post('/ressources/{ressource}', [RessourceValidationController::class, 'validate']); // Valider/rejeter une ressource
        Route::get('/ressources/{ressource}/history', [RessourceValidationController::class, 'history']); // Historique des validations
    });

});
