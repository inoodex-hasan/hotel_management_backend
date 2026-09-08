<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookingResource;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Get paginated list of all bookings (with optional status/search filters).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->input('per_page', 15), 50);

        $query = Booking::with([
            'hotelBooking.hotel.city.country',
            'hotelBooking.roomType',
            'passengers',
            'user',
        ])->latest('booked_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }

        if ($request->filled('search')) {
            $s = '%' . trim((string) $request->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('reference_no', 'like', $s)
                    ->orWhereHas('user', function ($uq) use ($s) {
                        $uq->where('name', 'like', $s)
                            ->orWhere('email', 'like', $s)
                            ->orWhere('phone', 'like', $s);
                    })
                    ->orWhereHas('passengers', function ($pq) use ($s) {
                        $pq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$s])
                            ->orWhere('email', 'like', $s)
                            ->orWhere('phone', 'like', $s);
                    });
            });
        }

        $bookings = $query->paginate($perPage);

        return BookingResource::collection($bookings);
    }

    /**
     * Standard Hotel Reservation Submission.
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        // Parameter normalizations from camelCase or frontend aliases
        $checkInVal = $input['check_in'] ?? $input['checkIn'] ?? null;
        $checkOutVal = $input['check_out'] ?? $input['checkOut'] ?? null;
        $firstName = $input['first_name'] ?? $input['firstName'] ?? '';
        $lastName = $input['last_name'] ?? $input['lastName'] ?? '';
        $phone = $input['phone'] ?? $input['paymentPhone'] ?? '';
        $email = $input['email'] ?? '';
        $roomsCount = (int) ($input['rooms_count'] ?? $input['rooms'] ?? 1);
        $adultGuests = (int) ($input['adult_guests'] ?? $input['adults'] ?? 2);
        $childGuests = (int) ($input['child_guests'] ?? $input['children'] ?? 0);
        $paymentMethod = $input['payment_method'] ?? $input['paymentMethod'] ?? 'pay-at-hotel';
        $couponCode = strtoupper(trim($input['coupon'] ?? $input['coupon_code'] ?? ''));
        $specialRequests = $input['special_requests'] ?? $input['specialRequests'] ?? null;
        $arrivalTime = $input['arrival_time'] ?? $input['arrivalTime'] ?? null;
        $address = $input['address'] ?? null;
        $city = $input['city'] ?? null;
        $country = $input['country'] ?? 'Bangladesh';
        $zip = $input['zip'] ?? $input['zip_code'] ?? null;

        // Resolve room type and hotel
        $roomSlug = $input['room_slug'] ?? $input['room'] ?? $input['slug'] ?? null;
        $roomTypeId = $input['room_type_id'] ?? null;
        $hotelId = $input['hotel_id'] ?? null;

        $roomType = null;
        if (!empty($roomTypeId)) {
            $roomType = RoomType::find($roomTypeId);
        } elseif (!empty($roomSlug)) {
            $roomType = RoomType::where('slug', $roomSlug)->first();
        }

        if (!$roomType) {
            // Default to first room type if none specified
            $roomType = RoomType::first();
        }

        if (!$roomType) {
            return response()->json([
                'message' => 'Selected room type is invalid or not available.',
                'errors' => ['room' => ['Room type not found.']],
            ], 422);
        }

        $hotel = $roomType->hotel_id ? Hotel::find($roomType->hotel_id) : null;
        if (!$hotel) {
            $hotel = Hotel::where('status', 'active')->first() ?? Hotel::firstOrFail();
        }

        // Validate essentials
        $request->merge([
            'check_in' => $checkInVal,
            'check_out' => $checkOutVal,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
        ]);

        $validated = $request->validate([
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:120'],
        ]);

        $checkIn = Carbon::parse($checkInVal);
        $checkOut = Carbon::parse($checkOutVal);
        $nights = max(1, $checkIn->diffInDays($checkOut));

        $basePrice = $nights * $roomsCount * (float) $roomType->base_price_per_night;
        $serviceFee = 0.00;
        $totalAmount = $basePrice + $serviceFee;

        // Coupon calculation
        $discountAmount = 0.00;
        if (!empty($couponCode)) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)
                ->where('is_active', true)
                ->first();

            if ($coupon && $coupon->isValidForAmount($totalAmount)) {
                $discountAmount = $coupon->calculateDiscount($totalAmount);
            }
        }

        $netAmount = max(0, $totalAmount - $discountAmount);

        // Payment details bundle
        $paymentDetails = [];
        if (in_array($paymentMethod, ['bkash', 'nagad'])) {
            $paymentDetails = [
                'provider' => $paymentMethod,
                'phone' => $input['paymentPhone'] ?? $phone,
                'transaction_id' => $input['transactionId'] ?? null,
            ];
        } elseif ($paymentMethod === 'card') {
            $cardNumber = $input['cardNumber'] ?? '';
            $maskedCard = strlen($cardNumber) >= 4 ? '**** **** **** ' . substr($cardNumber, -4) : 'Card';
            $paymentDetails = [
                'card_name' => $input['cardName'] ?? '',
                'card_masked' => $maskedCard,
                'card_expiry' => $input['cardExpiry'] ?? '',
            ];
        } elseif ($paymentMethod === 'bank') {
            $paymentDetails = [
                'bank_name' => $input['bankName'] ?? '',
                'bank_account' => $input['bankAccount'] ?? '',
                'bank_routing' => $input['bankRouting'] ?? '',
            ];
        }

        // Resolve or create user
        $user = $request->user();
        if (!$user) {
            $fullName = trim($firstName . ' ' . $lastName);
            $user = User::firstOrCreate(
                ['email' => strtolower($email)],
                [
                    'name' => $fullName !== '' ? $fullName : 'Guest',
                    'phone' => $phone,
                    'status' => 'active',
                    'password' => bcrypt(Str::random(16)),
                ]
            );
        }

        $booking = DB::transaction(function () use (
            $user, $totalAmount, $discountAmount, $netAmount, $nights, $roomsCount,
            $adultGuests, $childGuests, $roomType, $hotel, $checkInVal, $checkOutVal,
            $firstName, $lastName, $email, $phone, $paymentMethod, $paymentDetails,
            $couponCode, $specialRequests, $arrivalTime, $address, $city, $country, $zip, $input
        ) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'booking_type' => 'hotel',
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'net_amount' => $netAmount,
                'currency' => 'BDT',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'pay-at-hotel' ? 'unpaid' : 'pending_verification',
                'payment_details' => $paymentDetails,
                'coupon_code' => $couponCode ?: null,
                'notes' => $specialRequests,
                'booked_at' => now(),
            ]);

            HotelBooking::create([
                'booking_id' => $booking->id,
                'hotel_id' => $hotel->id,
                'room_type_id' => $roomType->id,
                'check_in' => $checkInVal,
                'check_out' => $checkOutVal,
                'nights' => $nights,
                'rooms_count' => $roomsCount,
                'adult_guests' => $adultGuests,
                'child_guests' => $childGuests,
                'total_price' => $netAmount,
                'special_requests' => $specialRequests,
                'arrival_time' => $arrivalTime,
                'address' => $address,
                'city' => $city,
                'country' => $country,
                'zip_code' => $zip,
            ]);

            // Create lead passenger
            BookingPassenger::create([
                'booking_id' => $booking->id,
                'first_name' => $firstName,
                'last_name' => $lastName ?: 'Guest',
                'email' => $email,
                'phone' => $phone,
                'type' => 'adult',
                'gender' => in_array($input['gender'] ?? '', ['male', 'female']) ? $input['gender'] : null,
                'nationality' => $country ?? 'Bangladesh',
                'is_lead_passenger' => true,
            ]);

            // Decrement inventory if available
            if ($roomType->available_rooms >= $roomsCount) {
                $roomType->decrement('available_rooms', $roomsCount);
            }

            return $booking;
        });

        $booking->load(['hotelBooking.hotel.city.country', 'hotelBooking.roomType', 'passengers', 'user']);

        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Quick Booking Submission.
     */
    public function storeQuick(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'room_type_id' => ['nullable', 'exists:room_types,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'rooms_count' => ['required', 'integer', 'min:1'],
            'adult_guests' => ['required', 'integer', 'min:1'],
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'email' => ['required', 'email', 'max:100'],
        ]);

        $hotel = Hotel::where('id', $validated['hotel_id'])
            ->where('status', 'active')
            ->with(['roomTypes' => fn ($q) => $q->orderBy('base_price_per_night', 'asc')])
            ->firstOrFail();

        $roomsCount = (int) $validated['rooms_count'];
        $adultGuests = (int) $validated['adult_guests'];

        $roomType = null;
        if (!empty($validated['room_type_id'])) {
            $roomType = $hotel->roomTypes->firstWhere('id', (int) $validated['room_type_id']);
        }
        if (!$roomType) {
            $roomType = $hotel->roomTypes->firstWhere(function ($type) use ($roomsCount) {
                return (int) $type->available_rooms >= $roomsCount;
            });
        }
        if (!$roomType) {
            return response()->json([
                'message' => 'No available room type found for this booking request.',
                'errors' => ['room_type_id' => ['No available room type found.']],
            ], 422);
        }
        if ((int) $roomType->available_rooms < $roomsCount) {
            return response()->json([
                'message' => 'Not enough rooms available.',
                'errors' => ['rooms_count' => ['Not enough rooms available.']],
            ], 422);
        }

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = max(1, $checkIn->diffInDays($checkOut));
        $basePrice = $nights * $roomsCount * (float) $roomType->base_price_per_night;
        $totalPrice = $basePrice + 1000.00;

        $fullName = trim($validated['full_name']);
        $nameParts = preg_split('/\s+/', $fullName);
        $firstName = $nameParts[0] ?? $fullName;
        $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '-';

        $user = $request->user();
        if (!$user) {
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $fullName,
                    'phone' => $validated['phone'],
                    'status' => 'active',
                    'password' => bcrypt(Str::random(16)),
                ]
            );
        }

        $booking = DB::transaction(function () use ($user, $totalPrice, $validated, $hotel, $roomType, $nights, $roomsCount, $adultGuests, $firstName, $lastName) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'booking_type' => 'hotel',
                'status' => 'pending',
                'total_amount' => $totalPrice,
                'discount_amount' => 0,
                'net_amount' => $totalPrice,
                'currency' => 'BDT',
                'notes' => 'Quick booking request via API',
                'booked_at' => now(),
            ]);

            HotelBooking::create([
                'booking_id' => $booking->id,
                'hotel_id' => $hotel->id,
                'room_type_id' => $roomType->id,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'nights' => $nights,
                'rooms_count' => $roomsCount,
                'adult_guests' => $adultGuests,
                'child_guests' => 0,
                'total_price' => $totalPrice,
                'special_requests' => null,
            ]);

            BookingPassenger::create([
                'booking_id' => $booking->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'type' => 'adult',
                'gender' => 'male',
                'nationality' => 'Bangladesh',
                'is_lead_passenger' => true,
            ]);

            $roomType->decrement('available_rooms', $roomsCount);

            return $booking;
        });

        $booking->load(['hotelBooking.hotel.city.country', 'hotelBooking.roomType', 'passengers', 'user']);

        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Look up booking details by Reference Number.
     */
    public function show(string $reference): BookingResource
    {
        $booking = Booking::where('reference_no', $reference)
            ->with(['hotelBooking.hotel.city.country', 'hotelBooking.roomType', 'passengers', 'user'])
            ->firstOrFail();

        return new BookingResource($booking);
    }

    /**
     * Get Authenticated User's Bookings.
     */
    public function userBookings(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->input('per_page', 10), 30);

        $bookings = Booking::where('user_id', $request->user()->id)
            ->with(['hotelBooking.hotel.city.country', 'hotelBooking.roomType', 'passengers'])
            ->latest('booked_at')
            ->paginate($perPage);

        return BookingResource::collection($bookings);
    }
}
