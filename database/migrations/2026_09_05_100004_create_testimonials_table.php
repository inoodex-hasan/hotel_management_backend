<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name');
            $table->string('location')->nullable(); // e.g. "New York, United States"
            $table->string('stay_room')->nullable(); // e.g. "Executive Room"
            $table->integer('rating')->default(5);
            $table->text('quote');
            $table->string('avatar')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
