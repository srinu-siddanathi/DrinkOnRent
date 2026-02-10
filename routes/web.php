<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\PurifierController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PaymentController;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// Default route
Route::get('/', function () {
    $roPlans = Plan::where('purifier_type', 'ro')->where('is_active', true)->orderBy('price')->get();
    $alkalinePlans = Plan::where('purifier_type', 'alkaline')->where('is_active', true)->orderBy('price')->get();
    return view('welcome', compact('roPlans', 'alkalinePlans'));
});

// Add a default login route
Route::get('/login', function() {
    return redirect()->route('admin.login');
})->name('login');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminController::class, 'loginForm'])->name('login');
        Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    });

    // Protected routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');

        // Customers
        Route::resource('customers', CustomerController::class);
        
        // Orders (Subscriptions)
        Route::resource('orders', OrderController::class);
        
        // Support Requests
        Route::resource('support-requests', SupportController::class);

        // Purifiers
        Route::resource('purifiers', PurifierController::class);

        // Plans
        Route::resource('plans', PlanController::class);

        // Payments
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });
});

Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');
Route::view('/terms-conditions', 'terms-conditions')->name('terms.conditions');

Route::post('/contact', function(Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'mobile' => 'required|digits:10',
        'message' => 'required|string',
    ]);

    Mail::raw(
        "Name: {$validated['name']}\nEmail: {$validated['email']}\nMobile: {$validated['mobile']}\nMessage: {$validated['message']}",
        function($message) {
            $message->to('srinu.vitam@gmail.com')
                    ->subject('New Contact Form Submission - Drink On Rent');
        }
    );

    return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
});