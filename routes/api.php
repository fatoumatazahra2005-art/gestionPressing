<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/services', [CatalogueController::class, 'index']);
Route::post('/services', [CatalogueController::class, 'store']);
Route::put('/services/{service}', [CatalogueController::class, 'update']);
Route::patch('/services/{service}/archive', [CatalogueController::class, 'archive']);
Route::patch('/services/{service}/reactivate', [CatalogueController::class, 'reactivate']);
Route::delete('/services/{service}', [CatalogueController::class, 'destroy']);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
