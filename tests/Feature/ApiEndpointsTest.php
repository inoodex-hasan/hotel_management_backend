<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use App\Models\HotelBooking;
use App\Models\HotelPolicy;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected Country $country;
    protected City $city;
    protected Hotel $hotel;
    protected RoomType $roomType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create([
            'name' => 'Bangladesh',
            'iso_code' => 'BD',
            'phone_code' => '+880',
        ]);

        $this->city = City::create([
            'country_id' => $this->country->id,
            'name' => 'Dhaka',
            'iata_code' => 'DAC',
        ]);

        $this->hotel = Hotel::create([
            'city_id' => $this->city->id,
            'name' => 'Radisson Blu Water Garden',
            'slug' => 'radisson-blu-water-garden',
            'description' => 'A luxury 5-star hotel in Dhaka.',
            'star_rating' => 5,
            'address' => 'Airport Road, Dhaka',
            'latitude' => 23.8103,
            'longitude' => 90.4125,
            'contact_phone' => '+8801700000000',
            'contact_email' => 'info@radissonbd.com',
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
            'is_international' => false,
            'status' => 'active',
            'meta_title' => 'Radisson Blu Dhaka Luxury Hotel',
            'meta_description' => 'Stay at Radisson Blu Dhaka.',
            'meta_keywords' => 'hotel, dhaka, luxury',
        ]);

        $this->roomType = RoomType::create([
            'hotel_id' => $this->hotel->id,
            'name' => 'Deluxe King Room',
            'description' => 'Spacious room with king-size bed.',
            'capacity_adults' => 2,
            'capacity_children' => 1,
            'total_rooms' => 10,
            'available_rooms' => 5,
            'base_price_per_night' => 8500.00,
            'is_refundable' => true,
            'cancellation_policy' => 'Free cancellation up to 24h before check-in',
        ]);

        HotelAmenity::create([
            'hotel_id' => $this->hotel->id,
            'amenity_name' => 'Free High-Speed WiFi',
            'icon' => 'wifi',
            'description' => 'Complimentary WiFi throughout the property',
            'is_featured' => true,
        ]);

        HotelPolicy::create([
            'hotel_id' => $this->hotel->id,
            'category' => 'check_in_out',
            'title' => 'Identification Required',
            'description' => 'Valid government-issued photo ID or passport is required.',
            'is_active' => true,
        ]);
    }

    public function test_get_settings(): void
    {
        $response = $this->getJson('/api/v1/settings');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'app_name',
                    'app_logo',
                    'app_favicon',
                    'contact_email',
                    'contact_phone',
                    'address',
                ],
            ]);
    }

    public function test_get_amenities(): void
    {
        $response = $this->getJson('/api/v1/amenities');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['amenity_name', 'icon'],
                ],
            ]);
    }

    public function test_get_hotels_index(): void
    {
        $response = $this->getJson('/api/v1/hotels');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'star_rating',
                        'address',
                        'starting_price',
                        'city',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_get_featured_hotels(): void
    {
        $response = $this->getJson('/api/v1/hotels/featured');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'star_rating', 'starting_price'],
                ],
            ]);
    }

    public function test_search_hotels(): void
    {
        $response = $this->getJson('/api/v1/hotels/search?location=Dhaka&rooms=1&guests=2&price_range=5001-10000&rating=5');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'radisson-blu-water-garden');
    }

    public function test_hotel_suggestions(): void
    {
        $response = $this->getJson('/api/v1/hotels/suggestions?q=Radisson');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'hotels',
                    'cities',
                ],
            ]);
    }

    public function test_get_hotel_detail(): void
    {
        $response = $this->getJson('/api/v1/hotels/radisson-blu-water-garden');
        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'radisson-blu-water-garden')
            ->assertJsonPath('data.star_rating', 5)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'star_rating',
                    'address',
                    'photos',
                    'city' => ['id', 'name'],
                    'room_types' => [
                        '*' => ['id', 'name', 'base_price_per_night', 'capacity_adults'],
                    ],
                    'amenities',
                    'policies',
                    'seo',
                ],
            ]);
    }

    public function test_get_destinations(): void
    {
        $response = $this->getJson('/api/v1/destinations');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'iata_code', 'hotels_count', 'country'],
                ],
            ]);
    }

    public function test_get_popular_destinations(): void
    {
        $response = $this->getJson('/api/v1/destinations/popular');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'hotels_count'],
                ],
            ]);
    }

    public function test_get_destination_detail(): void
    {
        $response = $this->getJson('/api/v1/destinations/' . $this->city->id);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'destination' => ['id', 'name'],
                    'hotels' => ['data', 'links', 'meta'],
                ],
            ]);
    }

    public function test_create_standard_booking(): void
    {
        $payload = [
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
            'rooms_count' => 1,
            'adult_guests' => 2,
            'child_guests' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+8801811223344',
            'gender' => 'male',
            'nationality' => 'Bangladeshi',
            'notes' => 'Airport pickup requested',
            'passengers' => [
                'adult' => [
                    ['first_name' => 'Jane', 'last_name' => 'Doe'],
                ],
                'child' => [
                    ['first_name' => 'Timmy', 'last_name' => 'Doe'],
                ],
            ],
        ];

        $response = $this->postJson('/api/v1/bookings', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'reference_no',
                    'booking_type',
                    'status',
                    'total_amount',
                    'hotel_booking' => [
                        'check_in',
                        'check_out',
                        'nights',
                        'rooms_count',
                        'hotel',
                        'room_type',
                    ],
                    'passengers',
                    'user',
                ],
            ]);

        $this->assertDatabaseHas('bookings', [
            'booking_type' => 'hotel',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('booking_passengers', [
            'email' => 'john@example.com',
            'is_lead_passenger' => 1,
        ]);

        // Inventory should decrement from 5 to 4
        $this->assertEquals(4, $this->roomType->fresh()->available_rooms);
    }

    public function test_get_all_bookings_index(): void
    {
        $user = User::create([
            'name' => 'John Listing',
            'email' => 'listing@example.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'reference_no' => 'BK-20261111',
            'booking_type' => 'hotel',
            'status' => 'pending',
            'total_amount' => 8500,
            'net_amount' => 8500,
            'currency' => 'BDT',
            'booked_at' => now(),
        ]);

        HotelBooking::create([
            'booking_id' => $booking->id,
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'nights' => 1,
            'rooms_count' => 1,
            'adult_guests' => 1,
            'child_guests' => 0,
            'total_price' => 8500,
        ]);

        $response = $this->getJson('/api/v1/bookings?status=pending');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'reference_no',
                        'booking_type',
                        'status',
                        'total_amount',
                        'hotel_booking',
                    ],
                ],
                'links',
                'meta',
            ])
            ->assertJsonPath('data.0.reference_no', 'BK-20261111');
    }

    public function test_show_booking_by_reference(): void
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'reference_no' => 'BK-20269999',
            'booking_type' => 'hotel',
            'status' => 'confirmed',
            'total_amount' => 9500,
            'net_amount' => 9500,
            'currency' => 'BDT',
            'booked_at' => now(),
        ]);

        HotelBooking::create([
            'booking_id' => $booking->id,
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
            'nights' => 1,
            'rooms_count' => 1,
            'adult_guests' => 2,
            'child_guests' => 0,
            'total_price' => 9500,
        ]);

        $response = $this->getJson('/api/v1/bookings/BK-20269999');

        $response->assertStatus(200)
            ->assertJsonPath('data.reference_no', 'BK-20269999')
            ->assertJsonPath('data.status', 'confirmed');
    }

    public function test_auth_register_login_user_logout(): void
    {
        // 1. Register
        $registerResponse = $this->postJson('/api/v1/auth/register', [
            'name' => 'Siam Ahmed',
            'email' => 'siam@example.com',
            'phone' => '+8801711223344',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $registerResponse->assertStatus(201)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => ['id', 'name', 'email'],
            ]);

        // 2. Login
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'siam@example.com',
            'password' => 'secret1234',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure(['access_token', 'user']);

        $token = $loginResponse->json('access_token');

        // 3. Get User Profile
        $userResponse = $this->withToken($token)->getJson('/api/v1/auth/user');
        $userResponse->assertStatus(200)
            ->assertJsonPath('user.email', 'siam@example.com');

        // 4. Logout
        $logoutResponse = $this->withToken($token)->postJson('/api/v1/auth/logout');
        $logoutResponse->assertStatus(200)
            ->assertJsonPath('message', 'Logged out successfully');
    }

    public function test_authenticated_user_bookings(): void
    {
        $user = User::create([
            'name' => 'Traveler User',
            'email' => 'traveler@test.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_type' => 'hotel',
            'status' => 'pending',
            'total_amount' => 5000,
            'net_amount' => 5000,
            'currency' => 'BDT',
            'booked_at' => now(),
        ]);

        HotelBooking::create([
            'booking_id' => $booking->id,
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'check_in' => now()->addDays(5)->toDateString(),
            'check_out' => now()->addDays(6)->toDateString(),
            'nights' => 1,
            'rooms_count' => 1,
            'adult_guests' => 1,
            'child_guests' => 0,
            'total_price' => 5000,
        ]);

        $response = $this->withToken($token)->getJson('/api/v1/auth/bookings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'reference_no', 'status', 'total_amount', 'hotel_booking'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_get_room_types_and_by_slug(): void
    {
        $this->roomType->update([
            'slug' => 'deluxe-king-room',
            'subtitle' => 'Comfort Redefined',
            'tag' => 'Popular',
            'floor' => '3rd Floor',
            'bed_type' => '1 King Bed',
            'room_size' => '320 sq ft',
            'stars' => 5,
            'highlights' => ['City View', 'Free Breakfast'],
        ]);

        $indexRes = $this->getJson('/api/v1/room-types');
        $indexRes->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'subtitle', 'tag', 'bed_type', 'room_size', 'stars', 'highlights'],
                ],
            ]);

        $showRes = $this->getJson('/api/v1/room-types/deluxe-king-room');
        $showRes->assertStatus(200)
            ->assertJsonPath('data.slug', 'deluxe-king-room')
            ->assertJsonPath('data.subtitle', 'Comfort Redefined')
            ->assertJsonPath('data.stars', 5);
    }

    public function test_get_facilities(): void
    {
        \App\Models\Facility::create([
            'hotel_id' => $this->hotel->id,
            'title' => 'Infinity Pool',
            'subtitle' => 'Relax & Refresh',
            'description' => 'Beautiful rooftop pool',
            'icon' => 'Waves',
            'features' => ['Heated', 'Bar'],
            'opening_hours' => '6 AM - 10 PM',
        ]);

        $response = $this->getJson('/api/v1/facilities');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Infinity Pool')
            ->assertJsonPath('data.0.icon', 'Waves');
    }

    public function test_get_dining(): void
    {
        \App\Models\Dining::create([
            'hotel_id' => $this->hotel->id,
            'slug' => 'the-restaurant',
            'name' => 'The Restaurant',
            'description' => 'Fine international cuisine',
            'location' => 'Lobby Level',
            'serves' => 'All day',
            'phone' => '+880170000000',
            'hours' => '7 AM - 11 PM',
            'features' => ['Fine Wine'],
        ]);

        $resIndex = $this->getJson('/api/v1/dining');
        $resIndex->assertStatus(200)
            ->assertJsonPath('data.0.slug', 'the-restaurant');

        $resShow = $this->getJson('/api/v1/dining/the-restaurant');
        $resShow->assertStatus(200)
            ->assertJsonPath('data.name', 'The Restaurant');
    }

    public function test_get_gallery_and_testimonials(): void
    {
        \App\Models\GalleryItem::create([
            'hotel_id' => $this->hotel->id,
            'title' => 'Grand Lobby',
            'category' => 'Interior',
            'image' => '/images/lobby.jpg',
        ]);

        \App\Models\Testimonial::create([
            'hotel_id' => $this->hotel->id,
            'guest_name' => 'John Reviewer',
            'location' => 'London, UK',
            'stay_room' => 'Deluxe Room',
            'rating' => 5,
            'quote' => 'Unbelievable stay!',
        ]);

        $resGallery = $this->getJson('/api/v1/gallery?category=Interior');
        $resGallery->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Grand Lobby');

        $resTestimonials = $this->getJson('/api/v1/testimonials');
        $resTestimonials->assertStatus(200)
            ->assertJsonPath('data.0.name', 'John Reviewer');
    }

    public function test_contact_and_newsletter(): void
    {
        $contactRes = $this->postJson('/api/v1/contact', [
            'name' => 'Guest Inquirer',
            'email' => 'guest@example.com',
            'phone' => '+8801900000000',
            'subject' => 'Honeymoon Package',
            'message' => 'Interested in honeymoon suites for next month.',
        ]);

        $contactRes->assertStatus(201)
            ->assertJsonPath('data.name', 'Guest Inquirer');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'guest@example.com',
        ]);

        $newsRes = $this->postJson('/api/v1/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $newsRes->assertStatus(200);
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
        ]);
    }

    public function test_coupon_validation(): void
    {
        \App\Models\Coupon::create([
            'code' => 'AZURA10',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'min_spend' => 5000.00,
            'is_active' => true,
        ]);

        $validRes = $this->postJson('/api/v1/coupons/validate', [
            'code' => 'AZURA10',
            'amount' => 10000,
        ]);

        $validRes->assertStatus(200)
            ->assertJsonPath('valid', true)
            ->assertJsonPath('data.discount_amount', 1000)
            ->assertJsonPath('data.final_amount', 9000);

        $invalidRes = $this->postJson('/api/v1/coupons/validate', [
            'code' => 'NONEXISTENT',
            'amount' => 10000,
        ]);

        $invalidRes->assertStatus(404)
            ->assertJsonPath('valid', false);
    }
}

