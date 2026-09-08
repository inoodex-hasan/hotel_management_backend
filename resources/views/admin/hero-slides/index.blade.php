@extends('admin.layouts.master')

@section('title', 'Hero Sliders')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Hero Sliders</h2>
            <p class="text-xs text-slate-400 mt-1">Manage dynamic slides displayed on the frontend home page hero banner</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="ltr:mr-1 rtl:ml-1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Add Hero Slide
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
                            <th class="py-3 px-4 text-left">Image</th>
                            <th class="py-3 px-4 text-left">Slide Details</th>
                            <th class="py-3 px-4 text-center">Hotel</th>
                            <th class="py-3 px-4 text-center">Badge</th>
                            <th class="py-3 px-4 text-center">Order</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $slide)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                                <td class="py-3 px-4">
                                    <div class="relative w-24 h-16 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-sm shrink-0">
                                        <img src="{{ $slide->image_full_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427'" />
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col max-w-xs">
                                        <span class="text-slate-800 dark:text-white font-bold text-sm">{{ $slide->title ?: 'Untitled Slide' }}</span>
                                        <span class="text-xs text-primary font-medium mt-0.5">{{ $slide->subtitle }}</span>
                                        @if($slide->description)
                                            <span class="text-[11px] text-slate-400 truncate mt-1">{{ Str::limit($slide->description, 60) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                        {{ $slide->hotel?->name ?? 'All / Default' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($slide->badge_text)
                                        <span class="badge bg-amber-500/10 text-info dark:text-info border border-amber-500/20 text-xs font-semibold px-2 py-0.5 rounded-full">
                                            {{ $slide->badge_text }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold font-mono text-slate-700 dark:text-slate-200">
                                    #{{ $slide->order }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($slide->is_active)
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
                                            <a href="{{ route('admin.hero-slides.edit', $slide) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Slide</span>
                                            </a>
                                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}"
                                                method="POST" onsubmit="return confirm('Are you sure you want to delete this slide?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Slide</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" class="text-slate-300 mb-2" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                            <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                                            <path d="M21 15L16 10L5 21" stroke="currentColor" stroke-width="1.5"/>
                                        </svg>
                                        <span class="text-base font-semibold">No hero slides found</span>
                                        <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary btn-sm mt-3">Create First Slide</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($slides->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $slides->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
