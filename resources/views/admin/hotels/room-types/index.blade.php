@extends('admin.layouts.master')

@section('title', 'Room types — '.$hotel->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Room Types & Photos</h2>
            <p class="text-xs text-slate-400 mt-1">{{ $hotel->name }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hotels.show', $hotel) }}" class="btn btn-secondary">View Hotel</a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">All Hotels</a>
            <a href="{{ route('admin.hotels.room-types.create', $hotel) }}" class="btn btn-primary">Add Room Type</a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 font-bold text-white-dark">
                            <th class="py-3 px-4 text-left">Photo</th>
                            <th class="py-3 px-4 text-left">Room Name & Slug</th>
                            <th class="py-3 px-4 text-center">Gallery</th>
                            <th class="py-3 px-4 text-center">Capacity</th>
                            <th class="py-3 px-4 text-center">Rooms (Avail / Total)</th>
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
                                <td class="py-3 px-4">
                                    <div class="relative w-16 h-12 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm shrink-0">
                                        <img src="{{ $mainImg }}" alt="{{ $rt->name }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427'" />
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold">
                                    <div class="flex flex-col">
                                        <span class="text-slate-800 dark:text-white font-bold">{{ $rt->name }}</span>
                                        <span class="text-xs text-primary font-mono mt-0.5">/rooms/{{ $rt->slug }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="badge {{ count($gallery) > 0 ? 'bg-info/10 text-info border border-info/20' : 'bg-slate-100 text-slate-400' }} text-xs font-bold px-2 py-0.5 rounded-md">
                                        {{ count($gallery) }} Photos
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">{{ $rt->capacity_adults }} Adults / {{ $rt->capacity_children }} Children</td>
                                <td class="py-3.5 px-4 text-center font-bold">
                                    <span class="{{ $rt->available_rooms > 0 ? 'text-success' : 'text-danger' }}">{{ $rt->available_rooms }}</span>
                                    <span class="text-slate-400 font-normal"> / {{ $rt->total_rooms }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-right font-bold text-slate-800 dark:text-white">
                                    ৳{{ number_format((float) $rt->base_price_per_night, 2) }}
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
                                        <div x-ref="menu" class="hidden w-48 rounded-xl bg-white p-1.5 shadow-2xl border border-slate-200 dark:border-slate-700 dark:bg-[#1b2e4b] text-slate-800 dark:text-slate-200 text-left">
                                            <a href="{{ route('admin.hotels.room-types.edit', [$hotel, $rt]) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Room & Photos</span>
                                            </a>
                                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                            <form action="{{ route('admin.hotels.room-types.destroy', [$hotel, $rt]) }}"
                                                method="POST" onsubmit="return confirm('Delete this room type?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Room</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400 font-semibold">No room types configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $roomTypes->links() }}</div>
        </div>
    </div>
@endsection
