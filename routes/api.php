<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogueController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;


Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);


Route::get('/services', [CatalogueController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::post('/services', [CatalogueController::class, 'store']);
    Route::put('/services/{service}', [CatalogueController::class, 'update']);
    Route::patch('/services/{service}/archive', [CatalogueController::class, 'archive']);
    Route::patch('/services/{service}/reactivate', [CatalogueController::class, 'reactivate']);
    Route::delete('/services/{service}', [CatalogueController::class, 'destroy']);

    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::patch('/tickets/{ticket}/en-traitement', [TicketController::class, 'passerEnTraitement']);
    Route::patch('/tickets/{ticket}/pret', [TicketController::class, 'marquerPret']);
    Route::patch('/tickets/{ticket}/recuperer', [TicketController::class, 'recuperer']);
    Route::patch('/tickets/{ticket}/annuler', [TicketController::class, 'annuler']);

});

use App\Http\Controllers\Api\PanierController;

Route::middleware('auth:api')->prefix('panier')->group(function () {
    Route::get('/', [PanierController::class, 'index']);
    Route::post('/items', [PanierController::class, 'ajouterItem']);
    Route::patch('/items/{serviceId}', [PanierController::class, 'modifierQuantite']);
    Route::delete('/items/{serviceId}', [PanierController::class, 'supprimerItem']);
    Route::delete('/', [PanierController::class, 'vider']);
    Route::post('/fusionner', [PanierController::class, 'fusionner']);
});
