<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AutenticacionSesion;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourtController;
use App\Http\Controllers\Api\RegistroUser;
use App\Http\Controllers\Api\TrainingController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegistroUser::class, 'store']); // Para registrarse
Route::post('/login', [AutenticacionSesion::class, 'store']); // Para iniciar sesion con un usuario existente

Route::middleware('MULTI-AUTH')->group(function () {

    // User
    Route::apiResource('user', UserController::class)->except(['index', 'store', 'destroy']);

    // Court
    Route::apiResource('court', CourtController::class)->except(['store', 'update', 'destroy', 'show']);
    Route::post('court', [CourtController::class, 'addUserToCourt']);

    // Activity
    Route::apiResource('activity', ActivityController::class)->except(['store', 'update', 'destroy', 'show']);

    // Training
    Route::apiResource('training', TrainingController::class)->except(['store', 'update', 'destroy']);
    Route::post('training', [TrainingController::class, 'addUserToTraining']);

    // Para cerrar sesión
    Route::post('/logout', [AutenticacionSesion::class, 'destroy']); 
});