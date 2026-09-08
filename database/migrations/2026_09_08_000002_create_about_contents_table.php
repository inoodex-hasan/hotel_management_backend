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
        Schema::create('about_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->onDelete('cascade');
            
            // Home About Section
            $table->string('eyebrow')->nullable()->default('Welcome to');
            $table->string('title')->nullable()->default('The Azura Hotel & Resort');
            $table->string('subtitle')->nullable()->default('A Luxury Beach View Hotel — A Perfect Combination Of Luxuriousness And Affordability.');
            $table->text('description')->nullable();
            $table->string('feature_1_title')->nullable()->default('Realistic Summer');
            $table->string('feature_1_subtitle')->nullable()->default('Vacation');
            $table->string('feature_2_title')->nullable()->default('Luxury Standard');
            $table->string('feature_2_subtitle')->nullable()->default('Hotel');
            $table->string('main_image_url')->nullable()->default('/images/room1.avif');
            $table->string('sub_image_url')->nullable()->default('/images/room2.avif');
            $table->string('button_text')->nullable()->default('Discover More');
            $table->string('button_link')->nullable()->default('/about');

            // About Page Story
            $table->string('since_year')->nullable()->default('Since 2018');
            $table->string('story_title')->nullable()->default('The Trusted Brand of Luxury Hospitality');
            $table->string('story_subtitle')->nullable()->default("Enjoy a Luxury Experience in Cox's Bazar");
            $table->text('story_description')->nullable();
            $table->string('contact_phone')->nullable()->default('+880 1401 777 888');
            $table->string('contact_email')->nullable()->default('reservation.theazura@gmail.com');
            $table->string('story_image_1')->nullable()->default('/images/about/about-hero.jpg');
            $table->string('story_image_2')->nullable()->default('/images/about/about-story.jpg');

            // Stats / Counters
            $table->integer('stat_rooms')->default(9);
            $table->string('stat_guests')->default('15000+');
            $table->string('stat_years')->default('6+');
            $table->string('stat_rating')->default('5');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_contents');
    }
};
