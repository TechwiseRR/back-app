<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessourceController;

Route::middleware('api')->group(function () {
    Route::apiResource('ressources', RessourceController::class);
});
