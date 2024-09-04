<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CarsController;
use App\Http\Controllers\Api\CarImagesController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para Categorías
Route::middleware('auth:sanctum')->group(function () {
    // Middleware para roles de lectura
    Route::middleware('role:lectura')->group(function() {
        Route::resource('categories', CategoriesController::class)->only(['index', 'show']);
        Route::resource('cars', CarsController::class)->only(['index', 'show']);
    });

    // Middleware para roles de lectura y edición
    Route::middleware('role:edicion')->group(function() {
        Route::resource('categories', CategoriesController::class)->except(['index', 'show']);
        Route::resource('cars', CarsController::class)->except(['index', 'show']);
    });

    // Middleware para roles de administrador
    Route::middleware('role:administrador')->group(function() {
        Route::resource('users', 'App\Http\Controllers\Api\UsersController');
    });

    // Rutas adicionales
    Route::get('cars/export', [CarsController::class, 'exportCsv']);
    Route::post('cars/{car}/images', [CarImagesController::class, 'store']);
    Route::delete('car_images/{carImage}', [CarImagesController::class, 'destroy']);
});

/*
Route::middleware('auth:sanctum')->group(function () {

    Route::resource('categories', CategoriesController::class);
    
    // Rutas para Coches
    Route::resource('cars', CarsController::class);
    Route::get('cars/export', [CarsController::class, 'exportCsv']);

    // Rutas para Imágenes de Coches
    Route::post('cars/{car}/images', [CarImagesController::class, 'store']);
    Route::delete('car_images/{carImage}', [CarImagesController::class, 'destroy']);

});
*/