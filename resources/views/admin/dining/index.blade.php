@extends('admin.layouts.master')

@section('title', 'Dining & Restaurants')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Dining & Restaurants</h2>
            <p class="text-xs text-slate-400 mt-1">Manage restaurant venues, dining descriptions, opening hours, menus, and photos</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.dining.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="ltr:mr-1 rtl:ml-1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Add Dining Venue
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 font-bold text-white-dark">
                            <th class="py-3 px-4 text-left">Photo</th>
                            <th class="py-3 px-4 text-left">Venue & Cuisine</th>
                            <th class="py-3 px-4 text-left">Location & Hours</th>
                            <th class="py-3 px-4 text-left">Features</th>
                            <th class="py-3 px-4 text-center">Sort Order</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venues as $venue)
                            @php
                                $imgSrc = $venue->image;
                                if ($imgSrc && (str_starts_with($imgSrc, '/storage/') || str_starts_with($imgSrc, 'storage/'))) {
                                    $imgSrc = url(ltrim($imgSrc, '/'));
                                }
                            @endphp
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                                <td class="py-3 px-4">
                                    <div class="relative w-24 h-16 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-sm shrink-0">
                                        <img src="{{ $imgSrc ?: '/images/dining/restaurant.avif' }}" alt="{{ $venue->name }}" class="w-full h-full object-cover" onerror="this.src='/favicon.ico'" />
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col max-w-xs">
                                        <span class="text-slate-800 dark:text-white font-bold text-sm">{{ $venue->name }}</span>
                                        @if($venue->label)
                                            <span class="text-[11px] text-primary font-medium mt-0.5 uppercase tracking-wider">{{ $venue->label }}</span>
                                        @endif
                                        @if($venue->serves)
                                            <span class="text-xs text-slate-500 dark:text-slate-400 mt-1">🍽 {{ $venue->serves }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col text-xs text-slate-600 dark:text-slate-300 gap-1">
                                        @if($venue->location)
                                            <span>📍 {{ $venue->location }}</span>
                                        @endif
                                        @if($venue->hours)
                                            <span class="text-slate-500 dark:text-slate-400">⏰ {{ $venue->hours }}</span>
                                        @endif
                                        @if($venue->phone)
                                            <span class="text-slate-400 font-mono">📞 {{ $venue->phone }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @if(is_array($venue->features) && count($venue->features) > 0)
                                            @foreach(array_slice($venue->features, 0, 3) as $feat)
                                                <span class="badge bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px] px-2 py-0.5 rounded">
                                                    {{ $feat }}
                                                </span>
                                            @endforeach
                                            @if(count($venue->features) > 3)
                                                <span class="text-[10px] text-slate-400">+{{ count($venue->features) - 3 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-slate-300 text-xs">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold font-mono text-slate-700 dark:text-slate-200">
                                    #{{ $venue->sort_order }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($venue->is_active)
                                        <span class="badge bg-success/10 text-success border border-success/20 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger/10 text-danger border border-danger/20 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            Inactive
                                        </span>
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
                                        <div x-ref="menu" class="hidden w-48 rounded-xl bg-white p-1.5 shadow-2xl border border-slate-200 dark:border-slate-700 dark:bg-[#1b2e4b] text-slate-800 dark:text-slate-200 text-left">
                                            <a href="{{ route('admin.dining.edit', $venue) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Venue</span>
                                            </a>
                                            <form action="{{ route('admin.dining.toggle', $venue) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-info dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                    <span>🔄</span>
                                                    <span>{{ $venue->is_active ? 'Mark Inactive' : 'Mark Active' }}</span>
                                                </button>
                                            </form>
                                            <div class="my-1 border-t border-slate-100 dark:border-slate-700"></div>
                                            <form action="{{ route('admin.dining.destroy', $venue) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this dining venue?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Venue</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400">
                                    No dining venues found. Click "Add Dining Venue" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $venues->links() }}
            </div>
        </div>
    </div>
@endsection
