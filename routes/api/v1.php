<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\PackageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ServiceController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    // Payment routes
    Route::post('payments/process', [PaymentController::class, 'process']);
    Route::get('payments/history', [PaymentController::class, 'history']);

    // Service routes
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{service}', [ServiceController::class, 'show']);

    // Package routes
    Route::get('packages', [PackageController::class, 'index']);
    Route::get('packages/{package}', [PackageController::class, 'show']);

    // Cart routes (protected by subscription middleware)
    Route::middleware('subscribed')->group(function() {
        Route::post('cart/add', [CartController::class, 'addToCart']);
    });
});