<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PaymentController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    // Payment routes
    Route::post('payments/process', [PaymentController::class, 'process']);
    Route::get('payments/history', [PaymentController::class, 'history']);
});