@extends('admin.layouts.master')

@section('title', 'Hotel Amenities — '.$hotel->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Hotel Amenities</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hotels.show', $hotel) }}" class="btn btn-secondary">View Hotel</a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">All Hotels</a>
            <a href="{{ route('admin.hotels.amenities.create', $hotel) }}" class="btn btn-primary">Add Amenity</a>
        </div>
    </div>

    <div class="panel mt-6">
        <p class="mb-4 text-sm text-white-dark">{{ $hotel->name }}</p>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Amenity Name</th>
                            <th>Description</th>
                            <th>Featured</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($amenities as $amenity)
                            <tr>
                                <td class="font-semibold">
                                    <div class="flex flex-col">
                                        <span>{{ $amenity->amenity_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <i class="{{ $amenity->icon }}"></i>
                                        <span>{{ $amenity->description ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($amenity->is_featured)
                                        <span class="badge badge-outline-primary">Yes</span>
                                    @else
                                        <span class="text-white-dark">-</span>
                                    @endif
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
                                            <a href="{{ route('admin.hotels.amenities.edit', [$hotel, $amenity]) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Amenity</span>
                                            </a>
                                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                            <form action="{{ route('admin.hotels.amenities.destroy', [$hotel, $amenity]) }}"
                                                method="POST" onsubmit="return confirm('Delete this amenity?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Amenity</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No amenities added.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
