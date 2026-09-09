@extends('admin.layouts.master')

@section('title', 'Guest Reviews & Testimonials')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Guest Reviews & Testimonials</h2>
            <p class="text-xs text-slate-400 mt-1">Manage guest stories, ratings, and quotes showcased on the website</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="ltr:mr-1 rtl:ml-1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Add Testimonial
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- FILTERS -->
    <div class="panel mt-4 p-4">
        <form method="GET" action="{{ route('admin.testimonials.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by guest name, location, stay, or quote..." class="form-input text-xs" />
            </div>
            <div class="w-36">
                <select name="rating" class="form-select text-xs">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Stars)</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Stars)</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 Stars)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
            @if(request()->hasAny(['search', 'rating', 'hotel_id']))
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="panel mt-4">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 font-bold text-white-dark">
                            <th class="py-3 px-4 text-left">Guest</th>
                            <th class="py-3 px-4 text-left">Stay & Location</th>
                            <th class="py-3 px-4 text-left">Rating</th>
                            <th class="py-3 px-4 text-left">Review Quote</th>
                            <th class="py-3 px-4 text-center">Featured</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonials as $testimonial)
                            @php
                                $avatarSrc = $testimonial->avatar;
                                if ($avatarSrc && (str_starts_with($avatarSrc, '/storage/') || str_starts_with($avatarSrc, 'storage/'))) {
                                    $avatarSrc = url(ltrim($avatarSrc, '/'));
                                }
                            @endphp
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-10 h-10 rounded-full overflow-hidden border border-slate-200 bg-primary/10 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                                            @if($avatarSrc)
                                                <img src="{{ $avatarSrc }}" alt="{{ $testimonial->guest_name }}" class="w-full h-full object-cover" onerror="this.style.display='none'" />
                                            @else
                                                <span>{{ strtoupper(substr($testimonial->guest_name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-slate-800 dark:text-white font-bold text-sm block">{{ $testimonial->guest_name }}</span>
                                            @if($testimonial->hotel)
                                                <span class="text-[11px] text-primary">{{ $testimonial->hotel->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col text-xs text-slate-600 dark:text-slate-300">
                                        <span class="font-semibold">{{ $testimonial->stay_room ?: 'Guest Stay' }}</span>
                                        <span class="text-slate-400 mt-0.5">{{ $testimonial->location ?: '—' }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center text-warning text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span>{{ $i <= $testimonial->rating ? '★' : '☆' }}</span>
                                        @endfor
                                        <span class="ml-1 font-bold text-slate-700 dark:text-slate-200 text-xs">({{ $testimonial->rating }}.0)</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="text-xs text-slate-600 dark:text-slate-300 max-w-sm line-clamp-2 italic">
                                        &ldquo;{{ $testimonial->quote }}&rdquo;
                                    </p>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($testimonial->is_featured)
                                        <span class="badge bg-primary/10 text-primary border border-primary/20 text-xs font-semibold px-2 py-0.5 rounded-full">
                                            Featured
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($testimonial->is_approved)
                                        <span class="badge bg-success/10 text-success border border-success/20 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            Published
                                        </span>
                                    @else
                                        <span class="badge bg-danger/10 text-danger border border-danger/20 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            Hidden
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
                                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Testimonial</span>
                                            </a>
                                            <form action="{{ route('admin.testimonials.toggle', $testimonial) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-info dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                    <span>🔄</span>
                                                    <span>{{ $testimonial->is_approved ? 'Hide from Website' : 'Publish Review' }}</span>
                                                </button>
                                            </form>
                                            <div class="my-1 border-t border-slate-100 dark:border-slate-700"></div>
                                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Review</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400">
                                    No testimonials found. Click "Add Testimonial" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>
@endsection
