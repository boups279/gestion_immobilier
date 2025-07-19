<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\ImmeubleController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaiementController;

// Routes publiques (authentification)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');  // <-- Ici on nomme la route login
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

// Routes protégées
Route::middleware(['auth:api'])->group(function () {
    
    // Routes Propriétaires
    Route::apiResource('proprietaires', ProprietaireController::class);
    
    // Routes Immeubles
    Route::apiResource('immeubles', ImmeubleController::class);
    Route::get('immeubles-disponibles', [ImmeubleController::class, 'disponibles']);
    
    // Routes Locataires
    Route::apiResource('locataires', LocataireController::class);
    
    // Routes Locations
    Route::apiResource('locations', LocationController::class);
    Route::patch('locations/{location}/terminer', [LocationController::class, 'terminer']);
    Route::get('locations-expire-bientot', [LocationController::class, 'expireeBientot']);
    
    // Routes Paiements
    Route::apiResource('paiements', PaiementController::class);
    Route::post('paiements/verifier', [PaiementController::class, 'verifierPaiement']);
    Route::get('paiements/mois', [PaiementController::class, 'paiementsPourMois']);
    Route::get('paiements-en-retard', [PaiementController::class, 'paivementsEnRetard']);
    Route::get('paiements/statistiques', [PaiementController::class, 'statistiques']);
});

// Routes admin uniquement
Route::middleware(['auth:api', 'admin'])->group(function () {
    // Ici vous pouvez ajouter des routes spécifiques aux admins
    // Par exemple: suppression définitive, gestion des utilisateurs, etc.
});