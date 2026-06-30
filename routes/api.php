<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    BookingController,
    OutletController,
    ServiceController,
    StylistController,
    CustomerController,
    CheckInController,
    WalkInController,
    PromotionController,
    AnalyticsController,
    ReviewController,
    DashboardController
};

// Define all routes inside a closure to register them under both prefixes
$apiRoutes = function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public resources
    Route::get('/outlets', [OutletController::class, 'index']);
    Route::get('/outlets/{id}', [OutletController::class, 'show']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/stylists', [StylistController::class, 'index']);
    Route::get('/stylists/{id}/availability', [StylistController::class, 'availability']);
    Route::get('/stylists/{id}/booked-dates', [StylistController::class, 'bookedDates']);
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);

    // Booking
    Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/check/{code}', [BookingController::class, 'check']);
    Route::post('/customer/check', [CustomerController::class, 'check']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Customer
        Route::get('/customers/profile', [CustomerController::class, 'profile']);
        Route::put('/customers/profile', [CustomerController::class, 'update']);
        Route::get('/customers/bookings', [CustomerController::class, 'bookings']);

        // Check-in
        Route::post('/check-in', [CheckInController::class, 'store']);
        Route::get('/check-in/{code}', [CheckInController::class, 'show']);

        // Walk-in
        Route::post('/walk-in', [WalkInController::class, 'store']);

        // Staff routes
        Route::middleware('role:staff')->group(function () {
            Route::get('/staff/queue', [DashboardController::class, 'queue']);
            Route::post('/staff/check-in/manual', [CheckInController::class, 'manual']);
        });

        // Stylist routes
        Route::middleware('role:stylist')->group(function () {
            Route::get('/stylist/schedule', [StylistController::class, 'schedule']);
            Route::post('/stylist/leave', [StylistController::class, 'requestLeave']);
        });

        // Admin routes
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('/outlets', OutletController::class)->except(['index', 'show']);
            Route::apiResource('/services', ServiceController::class)->except(['index']);
            Route::apiResource('/stylists', StylistController::class)->except(['index']);
            Route::apiResource('/customers', CustomerController::class);
            Route::apiResource('/bookings', BookingController::class);
            Route::apiResource('/promotions', PromotionController::class);
            Route::get('/analytics', [AnalyticsController::class, 'index']);
            Route::get('/analytics/export', [AnalyticsController::class, 'export']);
        });
    });
};

// Map both versions to support consistent prefixing across front-end models
Route::prefix('v1')->group($apiRoutes);
Route::group([], $apiRoutes);
