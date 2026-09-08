<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            if (!Schema::hasColumn('room_types', 'long_description')) {
                $table->text('long_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('room_types', 'image')) {
                $table->string('image')->nullable()->after('long_description');
            }
            if (!Schema::hasColumn('room_types', 'gallery')) {
                $table->json('gallery')->nullable()->after('image');
            }
            if (!Schema::hasColumn('room_types', 'amenities')) {
                $table->json('amenities')->nullable()->after('highlights');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('currency');
            }
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status', 30)->default('unpaid')->after('payment_method');
            }
            if (!Schema::hasColumn('bookings', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'coupon_code')) {
                $table->string('coupon_code', 50)->nullable()->after('payment_details');
            }
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('hotel_bookings', 'arrival_time')) {
                $table->string('arrival_time', 50)->nullable()->after('special_requests');
            }
            if (!Schema::hasColumn('hotel_bookings', 'address')) {
                $table->string('address', 255)->nullable()->after('arrival_time');
            }
            if (!Schema::hasColumn('hotel_bookings', 'city')) {
                $table->string('city', 100)->nullable()->after('address');
            }
            if (!Schema::hasColumn('hotel_bookings', 'country')) {
                $table->string('country', 100)->nullable()->after('city');
            }
            if (!Schema::hasColumn('hotel_bookings', 'zip_code')) {
                $table->string('zip_code', 30)->nullable()->after('country');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn(['long_description', 'image', 'gallery', 'amenities']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'payment_details', 'coupon_code']);
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn(['arrival_time', 'address', 'city', 'country', 'zip_code']);
        });
    }
};
