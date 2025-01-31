<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

// Force all routes in this file to return JSON
Route::group(['middleware' => ['api']], function() {
    // Test route to verify API is working
    Route::get('/test', function() {
        return response()->json(['message' => 'API is working']);
    });

    // Public routes
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Customer profile
        Route::put('/profile', [CustomerController::class, 'updateProfile']);
        Route::get('/dashboard', [CustomerController::class, 'dashboard']);

        // Plans
        Route::get('/plans', [PlanController::class, 'index']);
        Route::get('/plans/{plan}', [PlanController::class, 'show']);

        // Subscriptions
        Route::post('/subscriptions', [SubscriptionController::class, 'store']);
        Route::get('/subscriptions/active', [SubscriptionController::class, 'active']);
        Route::post('/subscriptions/{subscription}/activate', [SubscriptionController::class, 'activate']);
        Route::post('/subscriptions/{subscription}/pay', [PaymentController::class, 'processPayment']);
    });
}); 