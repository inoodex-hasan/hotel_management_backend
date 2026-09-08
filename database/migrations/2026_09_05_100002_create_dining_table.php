<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dining', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->nullable()->unique();
            $table->string('name');
            $table->string('label')->nullable(); // e.g. "BON APPÉTIT DURING VACATIONS"
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('location')->nullable(); // e.g. "Lobby Level", "Rooftop"
            $table->string('serves')->nullable(); // e.g. "Breakfast, Lunch, Dinner"
            $table->string('phone')->nullable();
            $table->string('hours')->nullable(); // e.g. "7:00 AM - 10:00 PM"
            $table->json('features')->nullable(); // e.g. ["International Menu", "Private Dining", "Ocean View"]
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dining');
    }
};
