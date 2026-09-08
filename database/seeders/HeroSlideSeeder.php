<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $azura = Hotel::where('slug', 'the-azura-hotel-suites')->first();
        $hotelId = $azura?->id;

        $slides = [
            [
                'hotel_id' => $hotelId,
                'title' => 'Escape to Refinement & Grace',
                'subtitle' => 'Welcome to The Azura',
                'description' => 'Where architectural elegance meets panoramic coastal luxury. Discover refined hospitality tailored to your highest expectations.',
                'badge_text' => '5-Star Luxury Experience',
                'button_text' => 'Explore Suites',
                'button_link' => '/rooms',
                'image_url' => '/images/room1.avif',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'hotel_id' => $hotelId,
                'title' => 'Unmatched Luxury & Ocean Panoramas',
                'subtitle' => 'World-Class Comfort',
                'description' => 'Indulge in private balconies, bespoke amenities, and personalized 24/7 concierge services in the heart of Cox\'s Bazar.',
                'badge_text' => 'Panoramic Sea Views',
                'button_text' => 'Book Your Stay',
                'button_link' => '/booking',
                'image_url' => '/images/room2.avif',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'hotel_id' => $hotelId,
                'title' => 'An Unforgettable Coastal Sanctuary',
                'subtitle' => 'Exclusive Living',
                'description' => 'Savor artisanal culinary creations, infinity rooftop pool, and restorative wellness therapies overlooking the azure horizon.',
                'badge_text' => 'Private Penthouse Living',
                'button_text' => 'Discover Dining',
                'button_link' => '/dining',
                'image_url' => '/images/room3.avif',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(
                [
                    'hotel_id' => $slide['hotel_id'],
                    'order' => $slide['order'],
                ],
                $slide
            );
        }
    }
}
