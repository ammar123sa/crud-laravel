<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// Public routes (غير محمية)
Route::apiResource('products', ProductController::class)->only(['index', 'show']);

// Protected routes (محمية)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
