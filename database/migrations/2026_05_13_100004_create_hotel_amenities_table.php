<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('amenity_name', 100);
            $table->string('icon', 100)->nullable();
            $table->timestamps();

            $table->unique(['hotel_id', 'amenity_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_amenities');
    }
};
