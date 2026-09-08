<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Dining;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TheAzuraSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Country & City
        $country = Country::firstOrCreate(
            ['iso_code' => 'BGD'],
            ['name' => 'Bangladesh', 'phone_code' => '+880']
        );

        $city = City::firstOrCreate(
            ['name' => 'Dhaka', 'country_id' => $country->id],
            ['iata_code' => 'DAC']
        );

        // 2. The Azura Hotel
        $hotel = Hotel::firstOrCreate(
            ['name' => 'The Azura Hotel & Suites'],
            [
                'slug' => 'the-azura-hotel-suites',
                'city_id' => $city->id,
                'description' => 'Experience supreme luxury, world-class dining, and bespoke hospitality at The Azura. Perfectly located with breathtaking city views, infinity pool, and world-class spa.',
                'address' => 'Plot 12, Road 45, Gulshan-2, Dhaka 1212',
                'latitude' => 23.7925,
                'longitude' => 90.4078,
                'star_rating' => 5,
                'check_in_time' => '14:00:00',
                'check_out_time' => '12:00:00',
                'contact_email' => 'concierge@theazura.com',
                'contact_phone' => '+880 1401 777 888',
                'status' => 'active',
            ]
        );
        if (empty($hotel->slug)) {
            $hotel->update(['slug' => 'the-azura-hotel-suites']);
        }

        // 3. Room Types (All 9 Rooms from Frontend roomsData.ts)
        $rooms = [
            [
                'slug' => 'premier-room',
                'name' => 'Premier Room',
                'subtitle' => 'Comfort Redefined',
                'tag' => 'Popular',
                'description' => 'Experience elegance in our beautifully designed Premier Room. Featuring modern amenities, plush bedding, and stunning city views, this room is perfect for both business and leisure travelers. Every detail has been thoughtfully curated to ensure your comfort.',
                'long_description' => 'The Premier Room at The Azura offers a perfect blend of comfort and style. Wake up to breathtaking city views through floor-to-ceiling windows, unwind in the plush king-size bed with premium linens, and enjoy the convenience of modern amenities designed for the contemporary traveler.',
                'image' => '/images/room1.avif',
                'gallery' => ['/images/rooms/room-1.avif', '/images/room1.avif', '/images/room2.avif'],
                'floor' => '3rd - 5th Floor',
                'bed_type' => '1 King Bed',
                'room_size' => '320 sq ft',
                'stars' => 4,
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'total_rooms' => 12,
                'available_rooms' => 10,
                'base_price_per_night' => 7500.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'Free High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '42-inch LED TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Mini Bar'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Rain Shower'],
                    ['icon' => 'Sparkles', 'label' => 'Daily Housekeeping'],
                    ['icon' => 'Lock', 'label' => 'In-Room Safe'],
                ],
                'highlights' => ['City View', 'Premium Linens', 'Blackout Curtains', 'Work Desk'],
            ],
            [
                'slug' => 'superior-deluxe-room',
                'name' => 'Superior Deluxe Room',
                'subtitle' => 'Spacious Luxury',
                'tag' => 'Best Seller',
                'description' => 'Our Superior Deluxe Room offers extra space and premium finishes. Enjoy the separate seating area, upgraded bathroom, and panoramic views that make your stay truly memorable.',
                'long_description' => 'Step into the Superior Deluxe Room and feel the difference. With 400 sq ft of thoughtfully designed space, a separate seating area for relaxation, and an upgraded bathroom with luxury toiletries, this room elevates your stay to new heights. Panoramic windows flood the room with natural light.',
                'image' => '/images/room2.avif',
                'gallery' => ['/images/rooms/room-2.avif', '/images/room3.avif', '/images/room1.avif'],
                'floor' => '4th - 6th Floor',
                'bed_type' => '1 King Bed',
                'room_size' => '400 sq ft',
                'stars' => 4,
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'total_rooms' => 10,
                'available_rooms' => 8,
                'base_price_per_night' => 9500.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'Free High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '50-inch Smart TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Mini Bar'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Rain Shower & Bathtub'],
                    ['icon' => 'Sparkles', 'label' => 'Daily Housekeeping'],
                    ['icon' => 'Lock', 'label' => 'In-Room Safe'],
                ],
                'highlights' => ['Panoramic View', 'Seating Area', 'Upgraded Bathroom', 'Nespresso Machine'],
            ],
            [
                'slug' => 'executive-room',
                'name' => 'Executive Room',
                'subtitle' => 'Business Class Comfort',
                'tag' => '',
                'description' => 'Designed for the discerning business traveler, the Executive Room combines functionality with luxury. Features a work desk, ergonomic chair, and premium connectivity.',
                'long_description' => 'The Executive Room is your personal workspace and retreat. Equipped with a spacious work desk, ergonomic chair, high-speed internet, and premium connectivity options, it is designed to keep you productive. After work, relax in the elegantly appointed living space.',
                'image' => '/images/room3.avif',
                'gallery' => ['/images/rooms/room-3.avif', '/images/room1.avif', '/images/rooms/room-2.avif'],
                'floor' => '6th - 8th Floor',
                'bed_type' => '1 King Bed / Twin Beds',
                'room_size' => '450 sq ft',
                'stars' => 4,
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'total_rooms' => 8,
                'available_rooms' => 6,
                'base_price_per_night' => 11000.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '55-inch Smart TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Nespresso Machine'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Rain Shower'],
                    ['icon' => 'Sparkles', 'label' => 'Daily Housekeeping'],
                    ['icon' => 'Lock', 'label' => 'In-Room Safe'],
                ],
                'highlights' => ['Work Desk', 'Ergonomic Chair', 'High-Speed Internet', 'USB Charging'],
            ],
            [
                'slug' => 'presidential-suite',
                'name' => 'Presidential Suite',
                'subtitle' => 'Ultimate Prestige',
                'tag' => 'Luxury',
                'description' => 'The pinnacle of luxury at The Azura. Our Presidential Suite features a private living room, dining area, panoramic terrace, and dedicated butler service for the most discerning guests.',
                'long_description' => 'The Presidential Suite represents the finest in luxury accommodation. Spanning 1200 sq ft of exquisitely designed space, it includes a private living room with designer furniture, a formal dining area for six, a panoramic terrace with stunning views, and a private jacuzzi. A dedicated butler is at your service around the clock.',
                'image' => '/images/rooms/room-4.avif',
                'gallery' => ['/images/rooms/room-4.avif', '/images/rooms/room-5.avif', '/images/room3.avif'],
                'floor' => '8th Floor',
                'bed_type' => '1 King Bed',
                'room_size' => '1200 sq ft',
                'stars' => 5,
                'capacity_adults' => 3,
                'capacity_children' => 2,
                'total_rooms' => 2,
                'available_rooms' => 2,
                'base_price_per_night' => 35000.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'Premium Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '65-inch OLED TV'],
                    ['icon' => 'Wind', 'label' => 'Zoned Climate Control'],
                    ['icon' => 'Coffee', 'label' => 'Full Private Bar'],
                    ['icon' => 'Utensils', 'label' => 'Private Dining'],
                    ['icon' => 'Bath', 'label' => 'Private Jacuzzi'],
                    ['icon' => 'Sparkles', 'label' => 'Butler Service 24/7'],
                    ['icon' => 'Lock', 'label' => 'Private Terrace'],
                ],
                'highlights' => ['Panoramic Terrace', 'Private Jacuzzi', 'Butler Service', 'Dining Area'],
            ],
            [
                'slug' => 'premier-suite',
                'name' => 'Premier Suite',
                'subtitle' => 'Elegant Living',
                'tag' => 'Family',
                'description' => 'A generous suite with separate living and sleeping areas. Perfect for extended stays or families, the Premier Suite offers home-like comfort with hotel luxury.',
                'long_description' => 'The Premier Suite is designed for those who appreciate space and elegance. With a separate living room, dining area, and a generously sized bedroom, it provides all the comforts of home with the luxury of a five-star hotel. Sea views from every window make this suite truly special.',
                'image' => '/images/room2.avif',
                'gallery' => ['/images/room2.avif', '/images/rooms/room-4.avif', '/images/room1.avif'],
                'floor' => '7th Floor',
                'bed_type' => '1 King Bed',
                'room_size' => '750 sq ft',
                'stars' => 5,
                'capacity_adults' => 3,
                'capacity_children' => 2,
                'total_rooms' => 4,
                'available_rooms' => 3,
                'base_price_per_night' => 18000.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'Free High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '55-inch Smart TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Mini Bar'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Rain Shower & Bathtub'],
                    ['icon' => 'Sparkles', 'label' => 'Daily Housekeeping'],
                    ['icon' => 'Lock', 'label' => 'Sea View'],
                ],
                'highlights' => ['Sea View', 'Separate Living Room', 'Dining Area', 'Premium Toiletries'],
            ],
            [
                'slug' => 'honeymoon-suite',
                'name' => 'Honeymoon Suite',
                'subtitle' => 'Romantic Escape',
                'tag' => 'Romantic',
                'description' => 'Crafted for love and celebration. Our Honeymoon Suite features a king-size bed with premium linens, rose petal turndown service, champagne on arrival, and breathtaking sunset views.',
                'long_description' => 'The Honeymoon Suite is where romance meets luxury. Every detail is designed to create unforgettable moments — from the rose petal turndown service to the champagne on arrival, from the king-size bed draped in the finest linens to the private balcony with breathtaking sunset views. Celebrate your love in the most beautiful setting.',
                'image' => '/images/room1.avif',
                'gallery' => ['/images/room1.avif', '/images/rooms/room-5.avif', '/images/room3.avif'],
                'floor' => '7th Floor',
                'bed_type' => '1 King Bed',
                'room_size' => '650 sq ft',
                'stars' => 5,
                'capacity_adults' => 2,
                'capacity_children' => 0,
                'total_rooms' => 3,
                'available_rooms' => 3,
                'base_price_per_night' => 22000.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'Free High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '50-inch Smart TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Full Bar'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Freestanding Bathtub'],
                    ['icon' => 'Sparkles', 'label' => 'Rose Petal Turndown'],
                    ['icon' => 'Lock', 'label' => 'Sunset View Balcony'],
                ],
                'highlights' => ['Sunset View', 'Champagne on Arrival', 'Rose Turndown', 'Freestanding Bathtub'],
            ],
            [
                'slug' => 'premier-suite-twin',
                'name' => 'Premier Suite Twin',
                'subtitle' => 'Elegant Living — Twin Beds',
                'tag' => 'Family',
                'description' => 'The Premier Suite Twin offers the same generous space and luxury as the Premier Suite, but with twin beds — ideal for friends or colleagues traveling together.',
                'long_description' => 'Enjoy the elegance and spaciousness of the Premier Suite with the added flexibility of twin beds. The Premier Suite Twin features a separate living room, dining area, and two comfortable twin beds with premium linens. Perfect for friends, colleagues, or family members who prefer separate sleeping arrangements without compromising on luxury.',
                'image' => '/images/rooms/room-5.avif',
                'gallery' => ['/images/rooms/room-5.avif', '/images/room2.avif', '/images/rooms/room-4.avif'],
                'floor' => '7th Floor',
                'bed_type' => '2 Twin Beds',
                'room_size' => '750 sq ft',
                'stars' => 5,
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'total_rooms' => 4,
                'available_rooms' => 4,
                'base_price_per_night' => 18000.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'Free High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '55-inch Smart TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Mini Bar'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Rain Shower & Bathtub'],
                    ['icon' => 'Sparkles', 'label' => 'Daily Housekeeping'],
                    ['icon' => 'Lock', 'label' => 'Sea View'],
                ],
                'highlights' => ['Sea View', 'Separate Living Room', 'Twin Beds', 'Premium Toiletries'],
            ],
            [
                'slug' => 'executive-room-double',
                'name' => 'Executive Room (Double)',
                'subtitle' => 'Business Comfort — Double Bed',
                'tag' => '',
                'description' => 'The Executive Room Double combines business functionality with the comfort of a double bed. Features a work desk, ergonomic chair, and premium connectivity for the professional traveler.',
                'long_description' => 'Designed for the business traveler who values comfort, the Executive Room Double features a spacious double bed, a dedicated work area with ergonomic chair, high-speed internet, and a Nespresso machine. After a productive day, unwind in the elegantly appointed room with views of the city skyline.',
                'image' => '/images/room3.avif',
                'gallery' => ['/images/room3.avif', '/images/room1.avif', '/images/room2.avif'],
                'floor' => '6th - 8th Floor',
                'bed_type' => '1 Double Bed',
                'room_size' => '450 sq ft',
                'stars' => 4,
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'total_rooms' => 6,
                'available_rooms' => 5,
                'base_price_per_night' => 11000.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '55-inch Smart TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Nespresso Machine'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Rain Shower'],
                    ['icon' => 'Sparkles', 'label' => 'Daily Housekeeping'],
                    ['icon' => 'Lock', 'label' => 'In-Room Safe'],
                ],
                'highlights' => ['Work Desk', 'Ergonomic Chair', 'Nespresso Machine', 'USB Charging'],
            ],
            [
                'slug' => 'executive-room-twin',
                'name' => 'Executive Room (Twin)',
                'subtitle' => 'Business Comfort — Twin Beds',
                'tag' => '',
                'description' => 'The Executive Room Twin offers twin bed configuration with the same business-class amenities. Perfect for colleagues sharing or travelers who prefer separate beds.',
                'long_description' => 'The Executive Room Twin provides all the business-class amenities of the Executive Room with the added comfort of twin beds. Whether you\'re traveling with a colleague or simply prefer separate sleeping arrangements, this room ensures a productive and comfortable stay with its dedicated workspace, high-speed connectivity, and premium amenities.',
                'image' => '/images/room3.avif',
                'gallery' => ['/images/room3.avif', '/images/room2.avif', '/images/room1.avif'],
                'floor' => '6th - 8th Floor',
                'bed_type' => '2 Twin Beds',
                'room_size' => '450 sq ft',
                'stars' => 4,
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'total_rooms' => 6,
                'available_rooms' => 5,
                'base_price_per_night' => 11000.00,
                'amenities' => [
                    ['icon' => 'Wifi', 'label' => 'High-Speed Wi-Fi'],
                    ['icon' => 'Tv', 'label' => '55-inch Smart TV'],
                    ['icon' => 'Wind', 'label' => 'Central Air Conditioning'],
                    ['icon' => 'Coffee', 'label' => 'Nespresso Machine'],
                    ['icon' => 'Utensils', 'label' => 'Room Service (24/7)'],
                    ['icon' => 'Bath', 'label' => 'Rain Shower'],
                    ['icon' => 'Sparkles', 'label' => 'Daily Housekeeping'],
                    ['icon' => 'Lock', 'label' => 'In-Room Safe'],
                ],
                'highlights' => ['Work Desk', 'Twin Beds', 'Nespresso Machine', 'USB Charging'],
            ],
        ];

        foreach ($rooms as $roomData) {
            RoomType::updateOrCreate(
                ['hotel_id' => $hotel->id, 'slug' => $roomData['slug']],
                $roomData
            );
        }

        // 4. Facilities (All 6 Facilities from Frontend)
        $facilities = [
            [
                'title' => 'Infinity Pool',
                'subtitle' => 'Relax & Refresh',
                'description' => 'Take a refreshing break and unwind beside our beautifully designed infinity pool. With stunning views and a serene atmosphere, it\'s the perfect place to soak up the sun and let your worries drift away.',
                'icon' => 'Waves',
                'image' => '/images/facilities/pool.webp',
                'features' => ['Heated Pool', 'Poolside Bar', 'Sun Loungers', 'Towel Service'],
                'opening_hours' => '6:00 AM - 10:00 PM',
                'sort_order' => 1,
            ],
            [
                'title' => 'Fine Dining',
                'subtitle' => 'Taste & Discover',
                'description' => 'Enjoy carefully crafted dishes prepared with the freshest local ingredients. Our signature restaurant offers an international menu featuring everything from traditional delicacies to global cuisines.',
                'icon' => 'Utensils',
                'image' => '/images/facilities/dining.avif',
                'features' => ['International Menu', 'Private Dining', 'Wine Collection', 'Ocean View'],
                'opening_hours' => '7:00 AM - 11:00 PM',
                'sort_order' => 2,
            ],
            [
                'title' => 'Spa & Wellness',
                'subtitle' => 'Relax & Rejuvenate',
                'description' => 'Restore your body and mind with our world-class wellness experience. Our authentic spa offers the perfect retreat — be it to heal, pamper, rejuvenate or revitalize, rest assured your desires will be met.',
                'icon' => 'Sparkles',
                'image' => '/images/facilities/spa.avif',
                'features' => ['Couples Treatment', 'Steam Room', 'Sauna', 'Hot Tub'],
                'opening_hours' => '9:00 AM - 9:00 PM',
                'sort_order' => 3,
            ],
            [
                'title' => 'Fitness Center',
                'subtitle' => 'Move & Energize',
                'description' => 'Stay active with state-of-the-art equipment available throughout your stay. Our modern fitness center features everything you need to maintain your workout routine while enjoying your vacation.',
                'icon' => 'Dumbbell',
                'image' => '/images/facilities/gym.webp',
                'features' => ['Modern Equipment', 'Personal Trainers', 'Yoga Studio', '24/7 Access'],
                'opening_hours' => '24 Hours',
                'sort_order' => 4,
            ],
            [
                'title' => 'Private Parking',
                'subtitle' => 'Easy & Convenient',
                'description' => 'Secure and convenient parking for a worry-free arrival. Our private parking area is monitored 24/7 to ensure your vehicle stays safe throughout your stay at The Azura.',
                'icon' => 'Car',
                'image' => '/images/facilities/parking.jpg',
                'features' => ['24/7 Security', 'CCTV Monitored', 'Covered Parking', 'Valet Service'],
                'opening_hours' => '24 Hours',
                'sort_order' => 5,
            ],
            [
                'title' => 'High-Speed Wi-Fi',
                'subtitle' => 'Always Connected',
                'description' => 'Stay connected with reliable high-speed Wi-Fi throughout the hotel. Whether for business or leisure, enjoy seamless internet access in every corner of The Azura.',
                'icon' => 'Wifi',
                'image' => '/images/facilities/wifi.avif',
                'features' => ['Fiber Optic', 'Room Service', 'Business Center', 'No Data Limits'],
                'opening_hours' => '24 Hours',
                'sort_order' => 6,
            ],
        ];

        foreach ($facilities as $fac) {
            Facility::updateOrCreate(
                ['hotel_id' => $hotel->id, 'title' => $fac['title']],
                $fac
            );
        }

        // 5. Dining Venues
        $venues = [
            [
                'slug' => 'the-restaurant',
                'name' => 'The Restaurant',
                'label' => 'BON APPÉTIT DURING VACATIONS AND TRIPS',
                'description' => 'Our experienced chefs create international specialties with unique flavors. Relax with gourmet cuisine and signature cocktails, all enhanced by beautiful music and gorgeous views. From seasonal menus to dining experiences to satisfy any craving, see what our chefs are preparing for you.',
                'image' => '/images/dining/restaurant.avif',
                'location' => 'Lobby Level',
                'serves' => 'Breakfast, Brunch, Lunch, Dinner, Dessert',
                'phone' => '+880 1401 777 888',
                'hours' => '7:00 AM - 10:00 PM',
                'features' => ['International Menu', 'Private Dining', 'Wine Collection', 'Ocean View'],
                'sort_order' => 1,
            ],
            [
                'slug' => 'the-rooftop-restaurant-bar',
                'name' => 'The Rooftop Restaurant & Bar',
                'label' => 'BON APPÉTIT DURING VACATIONS AND TRIPS',
                'description' => 'Enjoy the stunning rooftop views from The Azura\'s Rooftop Bar. Relax with gourmet cuisine and signature cocktails or homemade tonics, all enhanced by beautiful music and gorgeous views. Perfect for evening cocktails and starlit dinners.',
                'image' => '/images/dining/hotel-experience.avif',
                'location' => 'Rooftop',
                'serves' => 'Brunch, Lunch, Dinner, Wines',
                'phone' => '+880 1401 777 888',
                'hours' => '11:00 AM - Midnight',
                'features' => ['Skyline Views', 'Craft Cocktails', 'Live Music', 'Lounge Seating'],
                'sort_order' => 2,
            ],
        ];

        foreach ($venues as $venue) {
            Dining::updateOrCreate(
                ['hotel_id' => $hotel->id, 'slug' => $venue['slug']],
                $venue
            );
        }

        // 6. Gallery Items
        $gallery = [
            ['title' => 'Grand Lobby', 'category' => 'Interior', 'image' => '/images/room1.avif', 'sort_order' => 1],
            ['title' => 'Infinity Pool', 'category' => 'Experience', 'image' => '/images/room2.avif', 'sort_order' => 2],
            ['title' => 'Luxury Suite', 'category' => 'Rooms', 'image' => '/images/room3.avif', 'sort_order' => 3],
            ['title' => 'Signature Dining', 'category' => 'Dining', 'image' => '/images/dining/restaurant.avif', 'sort_order' => 4],
            ['title' => 'Wellness & Spa', 'category' => 'Wellness', 'image' => '/images/rooms/room-1.avif', 'sort_order' => 5],
            ['title' => 'Evening Lounge', 'category' => 'Lifestyle', 'image' => '/images/rooms/room-2.avif', 'sort_order' => 6],
        ];

        foreach ($gallery as $gItem) {
            GalleryItem::updateOrCreate(
                ['hotel_id' => $hotel->id, 'title' => $gItem['title']],
                $gItem
            );
        }

        // 7. Testimonials
        $testimonials = [
            [
                'guest_name' => 'Daniel Morgan',
                'stay_room' => 'Executive Room',
                'location' => 'New York, United States',
                'rating' => 5,
                'quote' => 'The Azura exceeded every expectation. The atmosphere was peaceful, the food was outstanding, and the staff made us feel genuinely welcome.',
                'is_featured' => true,
                'is_approved' => true,
            ],
            [
                'guest_name' => 'Emma Wilson',
                'stay_room' => 'Deluxe Suite',
                'location' => 'Melbourne, Australia',
                'rating' => 5,
                'quote' => 'A beautifully designed hotel with incredible attention to detail. Our weekend escape was exactly what we needed. We will definitely return.',
                'is_featured' => true,
                'is_approved' => true,
            ],
            [
                'guest_name' => 'Sofia Al-Mansoor',
                'stay_room' => 'Presidential Suite',
                'location' => 'Dubai, UAE',
                'rating' => 5,
                'quote' => 'An absolute masterclass in luxury hospitality. The butler service was immaculate, and the sunset views from the terrace are unmatched.',
                'is_featured' => true,
                'is_approved' => true,
            ],
        ];

        foreach ($testimonials as $test) {
            Testimonial::updateOrCreate(
                ['hotel_id' => $hotel->id, 'guest_name' => $test['guest_name']],
                $test
            );
        }

        // 8. Promotional Coupons
        $coupons = [
            [
                'code' => 'AZURA10',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_spend' => 5000,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME20',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_spend' => 10000,
                'is_active' => true,
            ],
            [
                'code' => 'VIP5000',
                'discount_type' => 'fixed',
                'discount_value' => 5000,
                'min_spend' => 25000,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coup) {
            Coupon::updateOrCreate(
                ['code' => $coup['code']],
                $coup
            );
        }
    }
}
