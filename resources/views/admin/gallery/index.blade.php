@extends('admin.layouts.master')

@section('title', 'Hotel Gallery')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Hotel Gallery Photos</h2>
            <p class="text-xs text-slate-400 mt-1">Manage guest experience & aesthetic photos showcased across the website</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="ltr:mr-1 rtl:ml-1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Add Gallery Photo
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
        <form method="GET" action="{{ route('admin.gallery.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by photo title..." class="form-input text-xs" />
            </div>
            <div class="w-44">
                <select name="category" class="form-select text-xs">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
            @if(request()->hasAny(['search', 'category', 'hotel_id']))
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="panel mt-4">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 font-bold text-white-dark">
                            <th class="py-3 px-4 text-left">Photo</th>
                            <th class="py-3 px-4 text-left">Title & Hotel</th>
                            <th class="py-3 px-4 text-left">Category</th>
                            <th class="py-3 px-4 text-center">Sort Order</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $gallery)
                            @php
                                $imgSrc = $gallery->image;
                                if ($imgSrc && (str_starts_with($imgSrc, '/storage/') || str_starts_with($imgSrc, 'storage/'))) {
                                    $imgSrc = url(ltrim($imgSrc, '/'));
                                }
                            @endphp
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                                <td class="py-3 px-4">
                                    <div class="relative w-24 h-16 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-sm shrink-0">
                                        <img src="{{ $imgSrc ?: '/images/room1.avif' }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover" onerror="this.src='/favicon.ico'" />
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col max-w-xs">
                                        <span class="text-slate-800 dark:text-white font-bold text-sm">{{ $gallery->title }}</span>
                                        @if($gallery->hotel)
                                            <span class="text-xs text-primary font-medium mt-0.5">{{ $gallery->hotel->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="badge bg-primary/10 text-primary border border-primary/20 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        {{ $gallery->category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold font-mono text-slate-700 dark:text-slate-200">
                                    #{{ $gallery->sort_order }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($gallery->is_active)
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
                                            <a href="{{ route('admin.gallery.edit', $gallery) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Photo</span>
                                            </a>
                                            <form action="{{ route('admin.gallery.toggle', $gallery) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-info dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                    <span>🔄</span>
                                                    <span>{{ $gallery->is_active ? 'Mark Inactive' : 'Mark Active' }}</span>
                                                </button>
                                            </form>
                                            <div class="my-1 border-t border-slate-100 dark:border-slate-700"></div>
                                            <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this gallery photo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Photo</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">
                                    No gallery photos found. Click "Add Gallery Photo" to upload one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection
