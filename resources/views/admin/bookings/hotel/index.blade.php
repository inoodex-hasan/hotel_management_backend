@extends('admin.layouts.master')

@section('title', 'Hotel Bookings')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Hotel Bookings</h2>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex w-full flex-col gap-5 md:flex-row md:items-end">
            <form action="{{ route('admin.bookings.hotel.index') }}" method="GET"
                class="flex w-full flex-1 flex-col gap-5 md:flex-row md:flex-wrap md:items-end">
                <div class="form-group w-full md:max-w-md md:min-w-[220px] md:flex-1">
                    <label for="search">Search</label>
                    <input type="text" name="search" id="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Reference #, Guest name, phone or email" />
                </div>
                <div class="form-group w-full md:w-44">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select pr-10">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group mb-0 w-full shrink-0 md:ml-auto md:w-auto">
                    <label for="booking-filter-submit" class="pointer-events-none select-none opacity-0" aria-hidden="true">Search</label>
                    <div class="flex flex-wrap gap-2">
                        <button id="booking-filter-submit" type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.bookings.hotel.index') }}" class="btn btn-outline-primary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Guest</th>
                            <th>Hotel / Room</th>
                            <th>Dates</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="font-bold text-primary">{{ $booking->reference_no }}</td>
                                <td>
                                    <div class="flex flex-col">
                                        @php
                                            $leadPassenger = $booking->passengers->firstWhere('is_lead_passenger', true) ?? $booking->passengers->first();
                                            $contactName = $leadPassenger ? trim(($leadPassenger->first_name ?? '') . ' ' . ($leadPassenger->last_name ?? '')) : ($booking->user->name ?? 'N/A');
                                            $contactPhone = $leadPassenger->phone ?? ($booking->user->phone ?? null);
                                            $contactEmail = $leadPassenger->email ?? ($booking->user->email ?? null);
                                        @endphp
                                        <span class="font-semibold">{{ $contactName ?: 'N/A' }}</span>
                                        @if($contactPhone)
                                            <span class="text-xs text-white-dark">{{ $contactPhone }}</span>
                                        @endif
                                        @if($contactEmail)
                                            <span class="text-xs text-white-dark">{{ $contactEmail }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($booking->hotelBooking)
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $booking->hotelBooking->hotel->name }}</span>
                                            <span class="text-xs text-white-dark">{{ $booking->hotelBooking->roomType->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-danger">Invalid Booking Data</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->hotelBooking)
                                        <div class="flex flex-col">
                                            <span>{{ $booking->hotelBooking->check_in->format('M d, Y') }}</span>
                                            <span class="text-xs text-white-dark">to {{ $booking->hotelBooking->check_out->format('M d, Y') }} ({{ $booking->hotelBooking->nights }} nights)</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-bold">{{ number_format($booking->net_amount, 2) }} {{ $booking->currency }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($booking->status) {
                                            'confirmed' => 'badge-outline-success',
                                            'completed' => 'badge-outline-info',
                                            'cancelled' => 'badge-outline-danger',
                                            default => 'badge-outline-warning',
                                        };
                                    @endphp
                                    <span class="badge capitalize {{ $statusClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div x-data="actionDropdown" class="inline-block text-center">
                                        <button type="button" x-ref="btn"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 hover:text-primary dark:text-slate-400 dark:hover:bg-dark-light/20 transition duration-150 focus:outline-none">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                                <circle cx="12" cy="5" r="2" fill="currentColor"/>
                                                <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                                <circle cx="12" cy="19" r="2" fill="currentColor"/>
                                            </svg>
                                        </button>
                                        <div x-ref="menu" class="hidden w-44 rounded-xl bg-white p-1.5 shadow-2xl border border-slate-200 dark:border-slate-700 dark:bg-[#1b2e4b] text-slate-800 dark:text-slate-200 text-left">
                                            <a href="{{ route('admin.bookings.hotel.show', $booking) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>👁️</span>
                                                <span>View Details</span>
                                            </a>
                                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                            <form action="{{ route('admin.bookings.hotel.destroy', $booking) }}" method="POST"
                                                onsubmit="return confirm('Delete this booking?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Booking</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $bookings->links() }}</div>
        </div>
    </div>
@endsection
