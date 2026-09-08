@extends('admin.layouts.master')

@section('title', 'All Room Types & Photos')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Room Types & Photos</h2>
            <p class="text-xs text-slate-400 mt-1">Manage all hotel room types, photos, galleries, and live pricing</p>
        </div>
        <div class="flex flex-wrap items-center justify-end gap-2">
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-primary">Manage Hotels</a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="panel mt-6">
        <form method="GET" action="{{ route('admin.room-types.all') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-bold text-white-dark uppercase">Search Room</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Room name, slug, bed type..." class="form-input" />
            </div>
            <div>
                <label class="text-xs font-bold text-white-dark uppercase">Filter by Hotel</label>
                <select name="hotel_id" class="form-select">
                    <option value="">All Hotels</option>
                    @foreach($hotels as $h)
                        <option value="{{ $h->id }}" {{ (string) request('hotel_id') === (string) $h->id ? 'selected' : '' }}>
                            {{ $h->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary w-full">Filter</button>
                <a href="{{ route('admin.room-types.all') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Room Types Grid & Table -->
    <div class="panel mt-6">
        <div class="table-responsive">
            <table class="table-striped table-hover w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 font-bold text-white-dark">
                        <th class="py-3 px-4 text-left">Photo</th>
                        <th class="py-3 px-4 text-left">Room Name & Slug</th>
                        <th class="py-3 px-4 text-left">Hotel</th>
                        <th class="py-3 px-4 text-center">Gallery Photos</th>
                        <th class="py-3 px-4 text-center">Capacity & Beds</th>
                        <th class="py-3 px-4 text-center">Available / Total</th>
                        <th class="py-3 px-4 text-right">Price / Night</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roomTypes as $rt)
                        @php
                            $gallery = is_array($rt->gallery) ? $rt->gallery : (json_decode($rt->gallery ?? '[]', true) ?: []);
                            $mainImg = $rt->image ?: ($gallery[0] ?? 'https://images.unsplash.com/photo-1590490360182-c33d57733427');
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <!-- Thumbnail Photo -->
                            <td class="py-3 px-4">
                                <div class="relative w-16 h-12 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm shrink-0">
                                    <img src="{{ $mainImg }}" alt="{{ $rt->name }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427'" />
                                </div>
                            </td>

                            <!-- Room Details -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $rt->name }}</span>
                                    <span class="text-xs text-primary font-mono mt-0.5">/rooms/{{ $rt->slug }}</span>
                                    @if($rt->tag)
                                        <span class="badge badge-outline-primary text-[10px] w-fit mt-1 px-1.5 py-0.5">{{ $rt->tag }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Hotel -->
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                                <span class="font-medium">{{ $rt->hotel?->name ?? 'Unassigned' }}</span>
                            </td>

                            <!-- Gallery Count -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <span class="badge {{ count($gallery) > 0 ? 'bg-info/10 text-info border border-info/20' : 'bg-slate-100 text-slate-400' }} text-xs font-bold px-2 py-0.5 rounded-md">
                                        {{ count($gallery) }} Photos
                                    </span>
                                </div>
                            </td>

                            <!-- Capacity & Beds -->
                            <td class="py-3.5 px-4 text-center text-xs">
                                <div class="font-semibold">{{ $rt->capacity_adults }} Adults, {{ $rt->capacity_children }} Children</div>
                                <div class="text-slate-400 mt-0.5">{{ $rt->bed_type ?: 'Standard Bed' }}</div>
                            </td>

                            <!-- Inventory -->
                            <td class="py-3.5 px-4 text-center font-bold">
                                <span class="{{ $rt->available_rooms > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $rt->available_rooms }}
                                </span>
                                <span class="text-slate-400 font-normal"> / {{ $rt->total_rooms }}</span>
                            </td>

                            <!-- Price -->
                            <td class="py-3.5 px-4 text-right font-black text-slate-800 dark:text-white text-base">
                                ৳{{ number_format((float)$rt->base_price_per_night, 2) }}
                            </td>

                            <!-- Actions -->
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
                                    <div x-ref="menu" class="hidden w-48 rounded-xl bg-white p-1.5 shadow-2xl border border-slate-200 dark:border-slate-700 dark:bg-[#1b2e4b] text-slate-800 dark:text-slate-200 text-left">
                                        @if($rt->hotel)
                                            <a href="{{ route('admin.hotels.room-types.edit', [$rt->hotel, $rt]) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Room & Photos</span>
                                            </a>
                                            <a href="{{ route('admin.hotels.room-types.index', $rt->hotel) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-info dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>🛏️</span>
                                                <span>Hotel Rooms List</span>
                                            </a>
                                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                            <form action="{{ route('admin.hotels.room-types.destroy', [$rt->hotel, $rt]) }}" method="POST" onsubmit="return confirm('Delete this room type?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Room</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-400 font-semibold">
                                No room types found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $roomTypes->links() }}
        </div>
    </div>
@endsection
