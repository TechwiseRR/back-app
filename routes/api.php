<?php

use App\Http\Controllers\InfoProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RessourceValidationController;
use App\Http\Controllers\VoteController;

// Authentification
Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
});

// Routes publiques (accessibles à tous - même non connectés)
Route::prefix('ressources')->group(function () {
    Route::get('/', [RessourceController::class, 'index']); // Lister les ressources
    Route::get('/{ressource}', [RessourceController::class, 'show']); // Afficher une ressource
});

// Routes publiques pour les catégories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Routes publiques pour les commentaires
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{id}', [CommentController::class, 'show']);
Route::get('/ressources/{id}/comments', [CommentController::class, 'getByRessource']);

// Route de désactivation
Route::post('/user/deactivate', [UserController::class, 'deactivate']);

// Routes protégées par auth (nécessitent une authentification)
Route::middleware(['auth:api', 'block.deactivated'])->group(function () {
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
    Route::post('/users/{user}/reactivate', [UserController::class, 'reactivate'])->middleware('block.deactivated');

// Déconnexion
    Route::post('/logout', [LogoutController::class, 'logout']);

// Notifications
    Route::apiResource('notifications', NotificationController::class);

// Permissions
    Route::apiResource('permissions', PermissionController::class);

// Informations de profil utilisateur
    Route::get('/info-profile', [InfoProfileController::class, 'index']);

// Commentaires (protégées)
    Route::post('/comments', [CommentController::class, 'add']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'remove']);
    Route::put('/comments/{id}/moderate', [CommentController::class, 'moderate']);
    Route::post('/comments/{id}/reply', [CommentController::class, 'reply']);

// Catégories (protégées)
    Route::post('/categories', [CategoryController::class, 'add']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'remove']);

// Routes pour la validation des ressources (modérateurs et admins)
    Route::prefix('validation')->group(function () {
        Route::get('/ressources', [RessourceValidationController::class, 'index']); // Liste des ressources à valider
        Route::post('/ressources/{ressource}', [RessourceValidationController::class, 'validate']); // Valider/rejeter une ressource
        Route::get('/ressources/{ressource}/history', [RessourceValidationController::class, 'history']); // Historique des validations
    });

// Route pour les votes (protégées)
    Route::get('/votes', [VoteController::class, 'index']);
    Route::get('/votes/{id}', [VoteController::class, 'show']);
    Route::post('/votes', [VoteController::class, 'add']);
    Route::put('/votes/{id}', [VoteController::class, 'update']);
    Route::delete('/votes/{id}', [VoteController::class, 'remove']);

});
