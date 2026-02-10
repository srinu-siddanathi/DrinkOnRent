<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SupportRequestController;
use App\Http\Controllers\PurifierController;
use Illuminate\Support\Facades\Route;

// Force all routes in this file to return JSON
Route::group(['middleware' => ['api']], function() {
   
    // Add this test route at the top of the group
    Route::get('/test', function() {
        return response()->json([
            'status' => 'success',
            'data' => [
                'plans' => [
                    ['id' => 1, 'name' => 'Basic Plan', 'price' => 99.99, 'data_limit' => '50GB'],
                    ['id' => 2, 'name' => 'Standard Plan', 'price' => 199.99, 'data_limit' => '100GB'],
                    ['id' => 3, 'name' => 'Premium Plan', 'price' => 299.99, 'data_limit' => 'Unlimited']
                ],
                'subscriptions' => [
                    ['id' => 1, 'user_id' => 1, 'plan_id' => 2, 'status' => 'active', 'expires_at' => '2024-04-30'],
                    ['id' => 2, 'user_id' => 2, 'plan_id' => 1, 'status' => 'pending', 'expires_at' => '2024-05-15']
                ]
            ],
            'message' => 'Test API is working!'
        ]);
    });

    // Public routes
    Route::post('/send-otp', [App\Http\Controllers\Api\AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [App\Http\Controllers\Api\AuthController::class, 'verifyOtp']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Registration
        Route::post('/register', [AuthController::class, 'register']);

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
        Route::post('/subscriptions/{subscription}/pay', [PaymentController::class, 'createOrder']);
        Route::post('/payment/verify', [PaymentController::class, 'verifyPayment']);

        // Update consumption details
        Route::put('/subscriptions/{subscription}/consumption', [SubscriptionController::class, 'updateConsumption']);

        // Support Requests
        Route::post('/support-requests', [SupportRequestController::class, 'store']);
        Route::get('/support-requests', [SupportRequestController::class, 'index']);
        Route::get('/support-requests/{supportRequest}', [SupportRequestController::class, 'show']);

        // Purifier Management Routes
        Route::post('/purifiers', [PurifierController::class, 'create']);
        Route::post('/purifiers/{purifierId}/plan', [PurifierController::class, 'setPlan']);
        Route::post('/purifiers/{purifierId}/rtc-error', [PurifierController::class, 'setRtcError']);
        Route::post('/purifiers/{purifierId}/clear-rtc-error', [PurifierController::class, 'clearRtcError']);
        Route::get('/current-datetime', [PurifierController::class, 'getCurrentDateTime']);
    });
}); 