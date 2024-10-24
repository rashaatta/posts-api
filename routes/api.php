<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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


// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/verify', [AuthController::class, 'verify'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Tags Routes
    Route::apiResource('tags', TagController::class);

    // Posts Routes
    Route::prefix('posts')->group(function () {
        Route::get('/deleted', [PostController::class, 'viewDeletedPosts']);
        Route::put('/restore/{id}', [PostController::class, 'restoreDeletedPost']);
    });
    Route::apiResource('posts', PostController::class);

    // Stats Route
    Route::get('/stats', [StatsController::class, 'index']);
});
