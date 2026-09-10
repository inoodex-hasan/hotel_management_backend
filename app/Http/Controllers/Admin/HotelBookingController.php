<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Carbon\Carbon;

class HotelBookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::where('booking_type', 'hotel')
            ->with(['user', 'hotelBooking.hotel', 'hotelBooking.roomType', 'passengers'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('reference_no', 'like', $s)
                    ->orWhereHas('user', function ($uq) use ($s) {
                        $uq->where('name', 'like', $s)
                            ->orWhere('email', 'like', $s);
                    });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.hotel.index', compact('bookings'));
    }

    public function create(): View
    {
        $hotels = Hotel::where('status', 'active')->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.bookings.hotel.create', compact('hotels', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'hotel_id' => ['required', 'exists:hotels,slug'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'rooms_count' => ['required', 'integer', 'min:1'],
            'adult_guests' => ['required', 'integer', 'min:1'],
            'child_guests' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
            'currency' => ['required', 'string', 'max:5'],
            'notes' => ['nullable', 'string'],
            'special_requests' => ['nullable', 'string'],
        ]);

        $hotel = Hotel::where('slug', $validated['hotel_id'])->firstOrFail();
        $roomType = RoomType::findOrFail($validated['room_type_id']);
        
        // Check availability
        if ($roomType->available_rooms < $validated['rooms_count']) {
            return back()->withInput()->with('error', 'Not enough rooms available.');
        }

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);

        $totalPrice = $nights * $validated['rooms_count'] * $roomType->base_price_per_night;

        DB::transaction(function () use ($validated, $totalPrice, $roomType) {
            $booking = Booking::create([
                'user_id' => $validated['user_id'],
                'booking_type' => 'hotel',
                'status' => $validated['status'],
                'total_amount' => $totalPrice,
                'net_amount' => $totalPrice, // No discount logic yet
                'currency' => $validated['currency'],
                'notes' => $validated['notes'],
                'booked_at' => now(),
            ]);

            HotelBooking::create([
                'booking_id' => $booking->id,
                'hotel_id' => $hotel->id,
                'room_type_id' => $validated['room_type_id'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'nights' => $checkIn->diffInDays($checkOut),
                'rooms_count' => $validated['rooms_count'],
                'adult_guests' => $validated['adult_guests'],
                'child_guests' => $validated['child_guests'],
                'total_price' => $totalPrice,
                'special_requests' => $validated['special_requests'],
            ]);

            // Decrement room inventory
            $roomType->decrement('available_rooms', $validated['rooms_count']);
        });

        return redirect()->route('admin.bookings.hotel.index')->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'hotelBooking.hotel', 'hotelBooking.roomType']);
        return view('admin.bookings.hotel.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($booking, $oldStatus, $newStatus) {
            $booking->update(['status' => $newStatus]);

            // If moving from active to cancelled, restore room inventory
            if (in_array($oldStatus, ['pending', 'confirmed']) && $newStatus === 'cancelled') {
                $hotelBooking = $booking->hotelBooking;
                if ($hotelBooking) {
                    RoomType::where('id', $hotelBooking->room_type_id)
                        ->increment('available_rooms', $hotelBooking->rooms_count);
                }
            }
            
            // If moving from cancelled back to active, re-decrement (if available)
            if ($oldStatus === 'cancelled' && in_array($newStatus, ['pending', 'confirmed'])) {
                $hotelBooking = $booking->hotelBooking;
                if ($hotelBooking) {
                    $roomType = RoomType::findOrFail($hotelBooking->room_type_id);
                    if ($roomType->available_rooms >= $hotelBooking->rooms_count) {
                        $roomType->decrement('available_rooms', $hotelBooking->rooms_count);
                    } else {
                        throw new \Exception('Cannot restore booking: Not enough rooms available.');
                    }
                }
            }
        });

        return redirect()->route('admin.bookings.hotel.show', $booking->id)->with('success', 'Booking status updated.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking) {
            if ($booking->status !== 'cancelled') {
                $hotelBooking = $booking->hotelBooking;
                if ($hotelBooking) {
                    RoomType::where('id', $hotelBooking->room_type_id)
                        ->increment('available_rooms', $hotelBooking->rooms_count);
                }
            }
            $booking->delete();
        });

        return redirect()->route('admin.bookings.hotel.index')->with('success', 'Booking deleted.');
    }

    public function getRoomTypes(Hotel $hotel): \Illuminate\Http\JsonResponse
    {
        $roomTypes = $hotel->roomTypes()
            ->select('id', 'name', 'base_price_per_night', 'available_rooms', 'capacity_adults', 'capacity_children')
            ->get();

        return response()->json($roomTypes);
    }
}
