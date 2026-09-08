@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <!-- Breadcrumb -->
    @if(auth()->user()?->hasRole('super-admin'))
        <!-- <ul class="flex space-x-2 rtl:space-x-reverse mb-6">
            <li>
                <a href="{{ route('tyro-dashboard.index') }}" class="text-primary hover:underline font-semibold">Dashboard</a>
            </li>
        </ul> -->

        <div class="space-y-6 pt-2">
            <!-- Main Business Metrics Grid -->
            <div>
                <h4 class="mb-4 text-base font-bold uppercase text-slate-800 dark:text-white-light tracking-wider">Business Overview</h4>
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    <!-- Total Revenue Card -->
                    <div class="panel h-full bg-gradient-to-br from-emerald-500/5 to-emerald-500/10 border border-emerald-500/10 shadow-sm rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-success bg-success/10 p-3 rounded-xl">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-2xl font-black text-slate-800 dark:text-white-light">৳{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Total Revenue</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Bookings Card -->
                    <div class="panel h-full bg-gradient-to-br from-blue-500/5 to-blue-500/10 border border-blue-500/10 shadow-sm rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-primary bg-primary/10 p-3 rounded-xl">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 2V6M8 2V6M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-2xl font-black text-slate-800 dark:text-white-light">{{ number_format($stats['total_bookings'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Total Bookings</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Hotels Card -->
                    <div class="panel h-full bg-gradient-to-br from-cyan-500/5 to-cyan-500/10 border border-cyan-500/10 shadow-sm rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-info bg-info/10 p-3 rounded-xl">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 7h6M9 11h6M9 15h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-2xl font-black text-slate-800 dark:text-white-light">{{ number_format($stats['total_hotels'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Total Hotels</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Destinations Card -->
                    <div class="panel h-full bg-gradient-to-br from-violet-500/5 to-violet-500/10 border border-violet-500/10 shadow-sm rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-secondary bg-secondary/10 p-3 rounded-xl">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-2xl font-black text-slate-800 dark:text-white-light">{{ number_format($stats['total_cities'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Destinations</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Hotel Bookings Table -->
            <div class="panel border border-slate-100 shadow-sm rounded-2xl p-5">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h5 class="text-lg font-bold text-slate-800 dark:text-white-light">Recent Hotel Bookings</h5>
                        <p class="text-xs text-slate-400 mt-0.5">Real-time status updates of guest reservations</p>
                    </div>
                    <a href="{{ route('admin.bookings.hotel.index') }}" class="btn btn-primary btn-sm flex items-center gap-1.5 font-bold shadow-md shadow-primary/20">
                        <span>View All Bookings</span>
                        <span>➔</span>
                    </a>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="table-hover table-striped table w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-slate-400">Ref No</th>
                                <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-slate-400">Lead Passenger</th>
                                <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-slate-400">Hotel & Room</th>
                                <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-slate-400 text-center">Dates & Nights</th>
                                <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-slate-400 text-right">Net Paid</th>
                                <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-slate-400 text-center">Status</th>
                                <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-slate-400 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                @php
                                    $hotelBooking = $booking->hotelBooking;
                                    $hotel = $hotelBooking?->hotel;
                                    $roomType = $hotelBooking?->roomType;
                                    $leadPassenger = $booking->passengers->first();
                                    
                                    $statusClass = 'bg-info/10 text-info border-info/20';
                                    if ($booking->status === 'confirmed' || $booking->status === 'active') {
                                        $statusClass = 'bg-success/10 text-success border-success/20';
                                    } elseif ($booking->status === 'cancelled') {
                                        $statusClass = 'bg-danger/10 text-danger border-danger/20';
                                    } elseif ($booking->status === 'pending') {
                                        $statusClass = 'bg-warning/10 text-warning border-warning/20';
                                    }
                                @endphp
                                <tr class="border-t border-slate-100 hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-3.5 px-4 font-bold text-primary">
                                        <span class="badge bg-primary/10 text-primary border border-primary/20 px-2.5 py-1 text-xs font-black rounded-lg">
                                            #{{ $booking->reference_no }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 dark:text-white-light">
                                                {{ $leadPassenger ? $leadPassenger->first_name . ' ' . $leadPassenger->last_name : ($booking->user?->name ?? 'N/A') }}
                                            </span>
                                            <span class="text-xs text-slate-400 mt-0.5">
                                                {{ $leadPassenger?->email ?? ($booking->user?->email ?? '') }}
                                            </span>
                                            @if($leadPassenger?->phone)
                                                <span class="text-xs text-slate-400">
                                                    {{ $leadPassenger->phone }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $coverPhoto = $hotel?->getMedia('photos')->first(fn($media) => $media->getCustomProperty('is_cover') === true) ?? $hotel?->getMedia('photos')->first();
                                                $thumbnailUrl = $coverPhoto ? $coverPhoto->getUrl() : 'https://images.unsplash.com/photo-1566073771259-6a8506099945';
                                            @endphp
                                            <img src="{{ $thumbnailUrl }}" alt="Cover" class="h-10 w-14 rounded-lg object-cover border border-slate-100">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-800 dark:text-white-light">{{ $hotel?->name ?? 'Unknown Hotel' }}</span>
                                                <span class="text-xs text-slate-400 mt-0.5">{{ $roomType?->name ?? 'Room Type' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs font-bold text-slate-700 dark:text-white-light">
                                                {{ $hotelBooking?->check_in?->format('d M, Y') ?? 'N/A' }} ➔ {{ $hotelBooking?->check_out?->format('d M, Y') ?? 'N/A' }}
                                            </span>
                                            <span class="text-xs text-slate-400 mt-1 font-semibold">
                                                {{ $hotelBooking?->nights ?? 0 }} Night(s) / {{ $hotelBooking?->rooms_count ?? 1 }} Room(s)
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black text-slate-800 dark:text-white-light text-base">
                                        ৳{{ number_format($booking->net_amount, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="badge {{ $statusClass }} uppercase text-[10px] font-black px-2.5 py-1 rounded-full border">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
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
                                    <td colspan="7" class="text-center py-10 text-slate-400 font-semibold">
                                        No recent bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Guest Engagement & Communications Overview -->
            <div>
                <h4 class="mb-4 text-base font-bold uppercase text-slate-800 dark:text-white-light tracking-wider">Guest Engagement & Communications</h4>
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-2">
                    <!-- Guest Inquiries -->
                    <a href="{{ route('admin.inquiries.index') }}" class="panel h-full border border-slate-100 shadow-sm rounded-2xl p-5 hover:border-primary/40 transition duration-150 group">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-warning bg-warning/10 p-3 rounded-xl group-hover:scale-110 transition-transform">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" fill="currentColor"/>
                                        <path d="M6 8L10.5528 11.0352C11.4343 11.6229 12.5657 11.6229 13.4472 11.0352L18 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <div class="flex items-center gap-2">
                                    <p class="text-2xl font-black text-slate-800 dark:text-white-light">{{ number_format($stats['total_inquiries'] ?? 0) }}</p>
                                    @if(($stats['unread_inquiries'] ?? 0) > 0)
                                        <span class="badge bg-danger/10 text-danger border border-danger/20 text-[10px] font-bold px-2 py-0.5 rounded-md">
                                            {{ $stats['unread_inquiries'] }} Unread
                                        </span>
                                    @endif
                                </div>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Guest Inquiries</h5>
                            </div>
                        </div>
                    </a>

                    <!-- Newsletter Subscribers -->
                    <a href="{{ route('admin.newsletter.index') }}" class="panel h-full border border-slate-100 shadow-sm rounded-2xl p-5 hover:border-primary/40 transition duration-150 group">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-info bg-info/10 p-3 rounded-xl group-hover:scale-110 transition-transform">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor"/>
                                        <path d="M7 12L10.5 15.5L17 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-2xl font-black text-slate-800 dark:text-white-light">{{ number_format($stats['total_subscribers'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Newsletter Subscribers</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Access Control & System Metrics Grid -->
            <div>
                <h4 class="mb-4 text-base font-bold uppercase text-slate-800 dark:text-white-light tracking-wider">Access Control & System Overview</h4>
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    <!-- Total Users -->
                    <div class="panel h-full border border-slate-100 shadow-sm rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-warning bg-warning/10 p-3 rounded-xl">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="2" />
                                        <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-xl font-bold text-slate-800 dark:text-white-light">{{ number_format($stats['total_users'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Total Administrators</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Roles -->
                    <div class="panel h-full border border-slate-100 shadow-sm rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-success bg-success/10 p-3 rounded-xl">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-xl font-bold text-slate-800 dark:text-white-light">{{ number_format($stats['total_roles'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Configured Roles</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Privileges -->
                    <div class="panel h-full border border-slate-100 shadow-sm rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-info bg-info/10 p-3 rounded-xl">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-4 rtl:mr-4">
                                <p class="text-xl font-bold text-slate-800 dark:text-white-light">{{ number_format($stats['total_privileges'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Access Privileges</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection