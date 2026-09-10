@extends('admin.layouts.master')

@section('title', 'Booking Details - ' . $booking->reference_no)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Booking: <span class="text-primary">{{ $booking->reference_no }}</span></h2>
        <div class="flex flex-wrap items-center justify-end gap-2">
            <a href="{{ route('admin.bookings.hotel.index') }}" class="btn btn-outline-primary">Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary Card -->
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold">Reservation Summary</h3>
                    @php
                        $statusClass = match($booking->status) {
                            'confirmed' => 'badge-outline-success',
                            'completed' => 'badge-outline-info',
                            'cancelled' => 'badge-outline-danger',
                            default => 'badge-outline-warning',
                        };
                    @endphp
                    <span class="badge capitalize {{ $statusClass }} text-sm py-1 px-4">
                        {{ $booking->status }}
                    </span>
                </div>

                @php
                    $leadPassenger = $booking->passengers->firstWhere('is_lead_passenger', true) ?? $booking->passengers->first();
                    $guestName = $leadPassenger ? ($leadPassenger->first_name . ' ' . $leadPassenger->last_name) : ($booking->user->name ?? 'N/A');
                    $guestEmail = $leadPassenger->email ?? $booking->user->email ?? 'N/A';
                    $guestPhone = $leadPassenger->phone ?? $booking->user->phone ?? 'N/A';
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-white-dark uppercase">Guest Name</span>
                            <span class="font-semibold text-base">{{ $guestName }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-white-dark uppercase">Email / Phone</span>
                            <span>{{ $guestEmail }} / {{ $guestPhone }}</span>
                        </div>
                        <div class="flex flex-col pt-3">
                            <span class="text-xs font-bold text-white-dark uppercase">Hotel</span>
                            <span class="font-semibold text-primary">{{ $booking->hotelBooking?->hotel?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-white-dark uppercase">Room Type</span>
                            <span class="font-medium">{{ $booking->hotelBooking?->roomType?->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-white-dark uppercase">Stay Duration</span>
                            <span class="font-semibold">
                                {{ $booking->hotelBooking?->check_in?->format('M d, Y') ?? 'N/A' }} — {{ $booking->hotelBooking?->check_out?->format('M d, Y') ?? 'N/A' }}
                            </span>
                            <span class="text-xs text-white-dark">({{ $booking->hotelBooking?->nights ?? 0 }} nights)</span>
                        </div>
                        <div class="flex flex-col pt-3">
                            <span class="text-xs font-bold text-white-dark uppercase">Rooms & Guests</span>
                            <span>{{ $booking->hotelBooking?->rooms_count ?? 1 }} Room(s), {{ $booking->hotelBooking?->adult_guests ?? 1 }} Adult(s), {{ $booking->hotelBooking?->child_guests ?? 0 }} Child(ren)</span>
                        </div>
                        <div class="flex flex-col pt-3">
                            <span class="text-xs font-bold text-white-dark uppercase">Net Paid / Payable</span>
                            <span class="text-2xl font-black text-primary">৳{{ number_format($booking->net_amount, 2) }} {{ $booking->currency }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment & Checkout Information -->
            <div class="panel">
                <h3 class="text-lg font-bold mb-4">Payment & Transaction Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-xs font-bold text-white-dark uppercase">Payment Method</span>
                            <span class="badge badge-outline-primary uppercase font-bold text-xs">
                                {{ str_replace('-', ' ', $booking->payment_method ?? 'Pay At Hotel') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-xs font-bold text-white-dark uppercase">Payment Status</span>
                            <span class="badge {{ $booking->payment_status === 'paid' ? 'badge-outline-success' : 'badge-outline-warning' }} uppercase font-bold text-xs">
                                {{ str_replace('_', ' ', $booking->payment_status ?? 'unpaid') }}
                            </span>
                        </div>

                        @php
                            $pDetails = is_array($booking->payment_details) ? $booking->payment_details : (json_decode($booking->payment_details ?? '[]', true) ?: []);
                        @endphp

                        @if(!empty($pDetails['phone']))
                            <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                                <span class="text-xs font-bold text-white-dark uppercase">Payment Sender Phone</span>
                                <span class="font-mono font-semibold">{{ $pDetails['phone'] }}</span>
                            </div>
                        @endif

                        @if(!empty($pDetails['transaction_id']))
                            <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                                <span class="text-xs font-bold text-white-dark uppercase">Transaction ID</span>
                                <span class="font-mono font-bold text-success">{{ $pDetails['transaction_id'] }}</span>
                            </div>
                        @endif

                        @if(!empty($pDetails['card_masked']))
                            <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                                <span class="text-xs font-bold text-white-dark uppercase">Card Number</span>
                                <span class="font-mono">{{ $pDetails['card_masked'] }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-xs font-bold text-white-dark uppercase">Subtotal</span>
                            <span class="font-semibold">৳{{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-xs font-bold text-white-dark uppercase">Coupon Applied</span>
                            <span>{{ $booking->coupon_code ? $booking->coupon_code : 'None' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-xs font-bold text-white-dark uppercase">Discount Amount</span>
                            <span class="text-danger font-semibold">-৳{{ number_format($booking->discount_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5">
                            <span class="text-xs font-bold text-white-dark uppercase">Final Net Amount</span>
                            <span class="text-lg font-black text-slate-800 dark:text-white">৳{{ number_format($booking->net_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Arrival & Billing Address -->
            <div class="panel">
                <h3 class="text-lg font-bold mb-4">Guest Arrival & Billing Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="space-y-2">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-white-dark uppercase">Estimated Arrival Time</span>
                            <span class="font-medium text-slate-800 dark:text-white mt-1">{{ $booking->hotelBooking?->arrival_time ?: 'Not specified' }}</span>
                        </div>
                        <div class="flex flex-col pt-2">
                            <span class="text-xs font-bold text-white-dark uppercase">Billing Address</span>
                            <span class="text-slate-800 dark:text-white mt-1">{{ $booking->hotelBooking?->address ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-white-dark uppercase">City / Zip</span>
                            <span class="text-slate-800 dark:text-white mt-1">{{ $booking->hotelBooking?->city ?: 'N/A' }} {{ $booking->hotelBooking?->zip_code ? '('.$booking->hotelBooking?->zip_code.')' : '' }}</span>
                        </div>
                        <div class="flex flex-col pt-2">
                            <span class="text-xs font-bold text-white-dark uppercase">Country</span>
                            <span class="text-slate-800 dark:text-white mt-1">{{ $booking->hotelBooking?->country ?: 'Bangladesh' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes & Requests -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="panel">
                    <h3 class="text-md font-bold mb-3">Special Requests</h3>
                    <div class="p-3 bg-white-light/30 rounded italic text-sm">
                        {{ $booking->hotelBooking?->special_requests ?: 'No special requests.' }}
                    </div>
                </div>
                <div class="panel">
                    <h3 class="text-md font-bold mb-3">Internal Notes</h3>
                    <div class="p-3 bg-white-light/30 rounded text-sm">
                        {{ $booking->notes ?: 'No internal notes.' }}
                    </div>
                </div>
            </div>

            <!-- Guest Manifest -->
            <div class="panel">
                <h3 class="text-lg font-bold mb-5">Guest Manifest ({{ $booking->passengers->count() }})</h3>
                <div class="table-responsive">
                    <table class="table-striped table-hover w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 font-bold text-white-dark">
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Name</th>
                                <th class="py-3 px-4 text-left">Type</th>
                                <th class="py-3 px-4 text-left">Gender</th>
                                <th class="py-3 px-4 text-left">Nationality</th>
                                <th class="py-3 px-4 text-left">Contact Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($booking->passengers as $index => $passenger)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-semibold">
                                        {{ $passenger->first_name }} {{ $passenger->last_name }}
                                        @if($passenger->is_lead_passenger)
                                            <span class="badge badge-outline-primary text-xs ml-2 py-0.5 px-2">Lead</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 capitalize">
                                        <span class="badge {{ $passenger->type === 'child' ? 'badge-outline-warning' : 'badge-outline-info' }} text-xs">
                                            {{ $passenger->type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 capitalize">{{ $passenger->gender ?? 'N/A' }}</td>
                                    <td class="py-3 px-4">{{ $passenger->nationality ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-white-dark">
                                        @if($passenger->email || $passenger->phone)
                                            {{ $passenger->email ?? 'No Email' }} <br>
                                            {{ $passenger->phone ?? 'No Phone' }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-slate-400">No passengers registered.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Update Status Card -->
            <div class="panel">
                <h3 class="text-lg font-bold mb-5">Change Status</h3>
                <form action="{{ route('admin.bookings.hotel.status', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Update Status</button>
                    </div>
                </form>
                
                @if($booking->status === 'cancelled')
                    <div class="mt-4 p-3 bg-danger/10 border border-danger/20 rounded text-danger text-xs text-center">
                        Inventory was restored for this cancellation.
                    </div>
                @endif
            </div>

            <!-- Meta Info -->
            <div class="panel">
                <h3 class="text-md font-bold mb-4">Metadata</h3>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-white-dark uppercase">Booked At</span>
                        <span>{{ $booking->booked_at ? $booking->booked_at->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white-dark uppercase">Created At</span>
                        <span>{{ $booking->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white-dark uppercase">Last Updated</span>
                        <span>{{ $booking->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="panel border-danger/20">
                <h3 class="text-md font-bold text-danger mb-4">Danger Zone</h3>
                <form action="{{ route('admin.bookings.hotel.destroy', $booking) }}" method="POST"
                    onsubmit="return confirm('Permanently delete this booking? This will restore inventory if not already cancelled.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-full">Delete Booking</button>
                </form>
            </div>
        </div>
    </div>
@endsection
