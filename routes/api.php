<?php

use App\Http\Controllers\Api\V1\AboutController;
use App\Http\Controllers\Api\V1\AmenityController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\DestinationController;
use App\Http\Controllers\Api\V1\DiningController;
use App\Http\Controllers\Api\V1\FacilityController;
use App\Http\Controllers\Api\V1\GalleryController;
use App\Http\Controllers\Api\V1\HeroSlideController;
use App\Http\Controllers\Api\V1\HotelController;
use App\Http\Controllers\Api\V1\RoomTypeController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Public Settings & Amenities
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/amenities', [AmenityController::class, 'index']);

    // Hotels Endpoints
    Route::prefix('hotels')->group(function () {
        Route::get('/', [HotelController::class, 'index']);
        Route::get('/featured', [HotelController::class, 'featured']);
        Route::get('/search', [HotelController::class, 'search']);
        Route::get('/suggestions', [HotelController::class, 'suggestions']);
        Route::get('/{slug}', [HotelController::class, 'show']);
    });

    // Room Types Endpoints (Direct lookup and filter)
    Route::prefix('room-types')->group(function () {
        Route::get('/', [RoomTypeController::class, 'index']);
        Route::get('/{slug}', [RoomTypeController::class, 'show']);
    });

    // Hotel Experience & Amenities
    Route::get('/hero-slides', [HeroSlideController::class, 'index']);
    Route::get('/about', [AboutController::class, 'index']);
    Route::get('/facilities', [FacilityController::class, 'index']);
    Route::prefix('dining')->group(function () {
        Route::get('/', [DiningController::class, 'index']);
        Route::get('/{slug}', [DiningController::class, 'show']);
    });
    Route::get('/gallery', [GalleryController::class, 'index']);
    Route::get('/testimonials', [TestimonialController::class, 'index']);

    // Guest Inquiries & Interactions
    Route::post('/contact', [ContactController::class, 'store']);
    Route::post('/newsletter', [ContactController::class, 'subscribe']);
    Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);

    // Destinations Endpoints
    Route::prefix('destinations')->group(function () {
        Route::get('/', [DestinationController::class, 'index']);
        Route::get('/popular', [DestinationController::class, 'popular']);
        Route::get('/{id}', [DestinationController::class, 'show']);
    });

    // Bookings Endpoints
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::post('/quick', [BookingController::class, 'storeQuick']);
        Route::get('/{reference}', [BookingController::class, 'show']);
    });

    // Authentication (Sanctum)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/user', [AuthController::class, 'user']);
            Route::get('/bookings', [BookingController::class, 'userBookings']);
        });
    });

});
