<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->unsignedBigInteger('saved_traveler_id')->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->enum('type', ['adult', 'child', 'infant']);
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('passport_number', 50)->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('seat_preference', 50)->nullable();
            $table->string('seat_number', 10)->nullable();
            $table->string('meal_preference', 50)->nullable();
            $table->boolean('is_lead_passenger')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_passengers');
    }
};