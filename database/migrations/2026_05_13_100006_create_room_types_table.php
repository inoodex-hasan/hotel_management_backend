<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->integer('capacity_adults');
            $table->integer('capacity_children')->default(0);
            $table->integer('total_rooms');
            $table->integer('available_rooms');
            $table->decimal('base_price_per_night', 10, 2);
            $table->boolean('is_refundable')->default(true);
            $table->text('cancellation_policy')->nullable();
            $table->timestamps();

            $table->unique(['hotel_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
