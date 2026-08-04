<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\PurifierController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\MasterDataController;
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

Route::get('/admin', function() {
    return redirect()->route('admin.login');
});

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
        Route::get('dashboard/revenue', [AdminController::class, 'revenue'])->name('dashboard.revenue');
        Route::get('dashboard/services-soon', [AdminController::class, 'servicesSoon'])->name('dashboard.services-soon');
        Route::get('dashboard/services-delay', [AdminController::class, 'servicesDelay'])->name('dashboard.services-delay');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');

        // Customers
        Route::get('customers/bin', [CustomerController::class, 'bin'])->name('customers.bin');
        Route::put('customers/{customer}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
        Route::delete('customers/{customer}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');
        Route::resource('customers', CustomerController::class);
        Route::get('customers/{customer}/download-id-proof', [CustomerController::class, 'downloadIdProof'])->name('customers.download-id-proof');
        Route::get('customers/{customer}/show-id-proof', [CustomerController::class, 'showIdProof'])->name('customers.show-id-proof');
        Route::get('customers-search', [CustomerController::class, 'search'])->name('customers.search');
        
        // Orders (Subscriptions)
        Route::resource('orders', OrderController::class);
        
        // Support Requests
        Route::resource('support-requests', SupportController::class);
        Route::get('support-requests-search', [SupportController::class, 'search'])->name('support-requests.search');

        // Purifier Requests
        Route::resource('purifier-requests', \App\Http\Controllers\Admin\PurifierRequestController::class)->only(['index', 'show', 'update']);

        // Purifiers
        Route::resource('purifiers', PurifierController::class);
        Route::get('purifiers-search', [PurifierController::class, 'search'])->name('purifiers.search');

        // Plans
        Route::resource('plans', PlanController::class);

        // Payments
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments-search', [PaymentController::class, 'search'])->name('payments.search');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

        // Services
        Route::resource('services', ServiceController::class);
        Route::get('services-search', [ServiceController::class, 'search'])->name('services.search');
        Route::get('customers/{customer}/service-history', [ServiceController::class, 'history'])->name('customers.service-history');

        // Complaints
        Route::resource('complaints', ComplaintController::class);
        Route::get('complaints-search', [ComplaintController::class, 'search'])->name('complaints.search');

        // Settings - Areas and Spare Parts Masters
        Route::get('settings/masters', [MasterDataController::class, 'index'])->name('settings.masters.index');
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('areas', [MasterDataController::class, 'areas'])->name('areas.index');
            Route::post('areas', [MasterDataController::class, 'storeArea'])->name('areas.store');
            Route::put('areas/{area}', [MasterDataController::class, 'updateArea'])->name('areas.update');
            Route::delete('areas/{area}', [MasterDataController::class, 'destroyArea'])->name('areas.destroy');

            Route::get('spare-parts', [MasterDataController::class, 'spareParts'])->name('spare-parts.index');
            Route::post('spare-parts', [MasterDataController::class, 'storeSparePart'])->name('spare-parts.store');
            Route::put('spare-parts/{sparePart}', [MasterDataController::class, 'updateSparePart'])->name('spare-parts.update');
            Route::delete('spare-parts/{sparePart}', [MasterDataController::class, 'destroySparePart'])->name('spare-parts.destroy');
        });
    });
});

Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');
Route::view('/terms-conditions', 'terms-conditions')->name('terms.conditions');
Route::view('/refunds-cancellation', 'refunds-cancellation')->name('refunds.cancellation');

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
