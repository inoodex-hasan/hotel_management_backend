<?php

namespace Database\Seeders;

use App\Models\AboutContent;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class AboutContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $azura = Hotel::where('slug', 'the-azura-hotel-suites')->first();
        $hotelId = $azura?->id;

        AboutContent::updateOrCreate(
            ['hotel_id' => $hotelId],
            [
                'hotel_id' => $hotelId,
                'eyebrow' => 'Welcome to',
                'title' => 'The Azura Hotel & Resort',
                'subtitle' => 'A Luxury Beach View Hotel — A Perfect Combination Of Luxuriousness And Affordability.',
                'description' => 'Situated on the picturesque coastline, The Azura Hotel & Resort offers unmatched convenience and accessibility. Our hotel stands as a beacon of comfort and elegance. With well-appointed rooms, each featuring a private balcony with direct sea views — we promise an experience like no other. Our top-tier amenities, including a swimming pool, gym, complimentary buffet breakfast, and free Wi-Fi, are thoughtfully designed to enhance your stay.',
                'feature_1_title' => 'Realistic Summer',
                'feature_1_subtitle' => 'Vacation',
                'feature_2_title' => 'Luxury Standard',
                'feature_2_subtitle' => 'Hotel',
                'main_image_url' => '/images/room1.avif',
                'sub_image_url' => '/images/room2.avif',
                'button_text' => 'Discover More',
                'button_link' => '/about',
                'since_year' => 'Since 2018',
                'story_title' => 'The Trusted Brand of Luxury Hospitality',
                'story_subtitle' => "Enjoy a Luxury Experience in Cox's Bazar",
                'story_description' => "Welcome to one of Cox's Bazar's most renowned landmarks, The Azura Hotel & Resort. Since it first opened its doors in 2018, thousands of visitors, including distinguished personalities, have been drawn to its elegant charm and warm hospitality. After years of being part of the tourism growth of Cox's Bazar, we understand the needs of well-travelled guests.",
                'contact_phone' => '+880 1401 777 888',
                'contact_email' => 'reservation.theazura@gmail.com',
                'story_image_1' => '/images/about/about-hero.jpg',
                'story_image_2' => '/images/about/about-story.jpg',
                'stat_rooms' => 9,
                'stat_guests' => '15000+',
                'stat_years' => '6+',
                'stat_rating' => '5',
            ]
        );
    }
}
