@extends('admin.layouts.master')

@section('title', 'Hotels')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Hotels</h2>
        <div class="flex flex-wrap items-center justify-end gap-2">
            <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary gap-2">Add hotel</a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex w-full flex-col gap-5 md:flex-row md:items-end">
            <form action="{{ route('admin.hotels.index') }}" method="GET"
                class="flex w-full flex-1 flex-col gap-5 md:flex-row md:flex-wrap md:items-end">
                <div class="form-group w-full md:max-w-md md:min-w-[220px] md:flex-1">
                    <label for="search">Search</label>
                    <input type="text" name="search" id="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Name or address" />
                </div>
                <div class="form-group w-full md:w-44">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select pr-10">
                        <option value="">All</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="form-group w-full md:w-56">
                    <label for="city_id">City</label>
                    <select name="city_id" id="city_id" class="form-select pr-10">
                        <option value="">All</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ (string) request('city_id') === (string) $city->id ? 'selected' : '' }}>
                                {{ $city->name }} ({{ $city->country->iso_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-0 w-full shrink-0 md:ml-auto md:w-auto">
                    <label for="hotel-filter-submit" class="pointer-events-none select-none opacity-0" aria-hidden="true">Search</label>
                    <div class="flex flex-wrap gap-2">
                        <button id="hotel-filter-submit" type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-primary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>Stars</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hotels as $hotel)
                            <tr>
                                <td class="font-semibold">{{ $hotel->name }}</td>
                                <td>{{ $hotel->city->name }}, {{ $hotel->city->country->name }}</td>
                                <td>{{ $hotel->star_rating }}</td>
                                <td>
                                    <span class="badge capitalize {{ $hotel->status === 'active' ? 'badge-outline-success' : 'badge-outline-dark' }}">
                                        {{ $hotel->status }}
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
                                        <div x-ref="menu" class="hidden w-48 rounded-xl bg-white p-1.5 shadow-2xl border border-slate-200 dark:border-slate-700 dark:bg-[#1b2e4b] text-slate-800 dark:text-slate-200 text-left">
                                            <a href="{{ route('admin.hotels.show', $hotel) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>👁️</span>
                                                <span>View Overview</span>
                                            </a>
                                            <a href="{{ route('admin.hotels.edit', $hotel) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Hotel</span>
                                            </a>
                                            <a href="{{ route('admin.hotels.room-types.index', $hotel) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-info dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>🛏️</span>
                                                <span>Manage Rooms</span>
                                            </a>
                                            <a href="{{ route('admin.hotels.amenities.index', $hotel) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-success dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✨</span>
                                                <span>Amenities</span>
                                            </a>
                                            <a href="{{ route('admin.hotels.photos.index', $hotel) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-warning dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>📸</span>
                                                <span>Photos & Gallery</span>
                                            </a>
                                            <a href="{{ route('admin.hotels.policies.index', $hotel) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-secondary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>📋</span>
                                                <span>Policies</span>
                                            </a>
                                            
                                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>

                                            <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST"
                                                onsubmit="return confirm('Remove this hotel?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Hotel</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hotels yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $hotels->links() }}</div>
        </div>
    </div>
@endsection
