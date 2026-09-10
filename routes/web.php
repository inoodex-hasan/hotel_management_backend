<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\HotelRoomTypeController;
use App\Http\Controllers\Admin\HotelAmenityController;
use App\Http\Controllers\Admin\HotelPhotoController;
use App\Http\Controllers\Admin\HotelPolicyController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\HotelBookingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\RoomTypeOverviewController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\AboutContentController;
use App\Http\Controllers\Admin\DiningController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\RoleController as LocalRoleController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('tyro-dashboard.index');
    }
    return redirect()->route('tyro-login.login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('tyro-dashboard.index');

    Route::prefix('dashboard')->name('admin.')->group(function () {
        Route::get('room-types', [RoomTypeOverviewController::class, 'index'])->name('room-types.all');
        Route::resource('hero-slides', HeroSlideController::class);
        Route::get('about', [AboutContentController::class, 'edit'])->name('about.edit');
        Route::post('about', [AboutContentController::class, 'update'])->name('about.update');
        Route::resource('dining', DiningController::class);
        Route::patch('dining/{dining}/toggle', [DiningController::class, 'toggleStatus'])->name('dining.toggle');
        Route::resource('facilities', FacilityController::class);
        Route::patch('facilities/{facility}/toggle', [FacilityController::class, 'toggleStatus'])->name('facilities.toggle');
        Route::resource('gallery', GalleryController::class);
        Route::patch('gallery/{gallery}/toggle', [GalleryController::class, 'toggleStatus'])->name('gallery.toggle');
        Route::resource('testimonials', TestimonialController::class);
        Route::patch('testimonials/{testimonial}/toggle', [TestimonialController::class, 'toggleApproval'])->name('testimonials.toggle');
        Route::resource('hotels', HotelController::class);
        Route::resource('hotels.room-types', HotelRoomTypeController::class)->except(['show']);
        Route::resource('hotels.amenities', HotelAmenityController::class)->except(['show']);
        Route::resource('hotels.photos', HotelPhotoController::class)->except(['show']);
        Route::resource('hotels.policies', HotelPolicyController::class);
        Route::resource('countries', CountryController::class);
        Route::resource('cities', CityController::class);

        // Guest Inquiries
        Route::prefix('inquiries')->name('inquiries.')->group(function () {
            Route::get('/', [ContactMessageController::class, 'index'])->name('index');
            Route::get('/{inquiry}', [ContactMessageController::class, 'show'])->name('show');
            Route::patch('/{inquiry}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('toggle-read');
            Route::delete('/{inquiry}', [ContactMessageController::class, 'destroy'])->name('destroy');
        });

        // Newsletter Subscribers
        Route::prefix('newsletter')->name('newsletter.')->group(function () {
            Route::get('/', [NewsletterSubscriberController::class, 'index'])->name('index');
            Route::patch('/{subscriber}/toggle-status', [NewsletterSubscriberController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{subscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('dashboard/settings')->name('admin.settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/update', [SettingController::class, 'update'])->name('update');
    });

    Route::prefix('dashboard/bookings')->name('admin.bookings.')->group(function () {
        // Hotel bookings
        Route::prefix('hotel')->name('hotel.')->group(function () {
            Route::get('/', [HotelBookingController::class, 'index'])->name('index');
            Route::get('/create', [HotelBookingController::class, 'create'])->name('create');
            Route::post('/', [HotelBookingController::class, 'store'])->name('store');
            Route::get('/{booking}', [HotelBookingController::class, 'show'])->name('show');
            Route::patch('/{booking}/status', [HotelBookingController::class, 'updateStatus'])->name('status');
            Route::delete('/{booking}', [HotelBookingController::class, 'destroy'])->name('destroy');
        });

        // AJAX
        Route::get('/hotel-room-types/{hotel}', [HotelBookingController::class, 'getRoomTypes'])->name('hotel.room-types');
    });

    // Role Management Overrides
    Route::prefix('dashboard/roles')->name('tyro-dashboard.roles.')->group(function () {
        Route::get('/', [LocalRoleController::class, 'index'])->name('index');
        Route::get('/create', [LocalRoleController::class, 'create'])->name('create');
        Route::post('/', [LocalRoleController::class, 'store'])->name('store');
        Route::get('{id}/edit', [LocalRoleController::class, 'edit'])->name('edit');
        Route::put('{id}', [LocalRoleController::class, 'update'])->name('update');
        Route::post('{id}/toggle', [LocalRoleController::class, 'toggleStatus'])->name('toggle');
        Route::delete('{id}', [LocalRoleController::class, 'destroy'])->name('destroy');
    });
});
