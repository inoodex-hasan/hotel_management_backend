@extends('admin.layouts.master')

@section('title', 'Hotel Policies — '.$hotel->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Hotel Policies</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hotels.show', $hotel) }}" class="btn btn-secondary">View Hotel</a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">All Hotels</a>
            <a href="{{ route('admin.hotels.policies.create', $hotel) }}" class="btn btn-primary">Add Policy</a>
        </div>
    </div>

    <div class="panel mt-6">
        <p class="mb-4 text-sm text-white-dark">{{ $hotel->name }}</p>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($policies as $policy)
                            <tr>
                                <td>
                                    <span class="badge badge-outline-info uppercase text-[10px]">
                                        {{ str_replace('_', ' ', $policy->category) }}
                                    </span>
                                </td>
                                <td class="font-semibold">
                                    <div class="flex flex-col">
                                        <span>{{ $policy->title }}</span>
                                        <!-- <span class="text-[10px] font-normal text-white-dark">
                                            {!! Str::limit($policy->description, 60) !!}
                                        </span> -->
                                    </div>
                                </td>
                                <td>
                                    {!! Str::limit($policy->description, 60) !!}
                                </td>
                                <td>
                                    @if($policy->is_active)
                                        <span class="badge badge-outline-success">Active</span>
                                    @else
                                        <span class="badge badge-outline-danger">Inactive</span>
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
                                            <a href="{{ route('admin.hotels.policies.show', [$hotel, $policy]) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>👁️</span>
                                                <span>View Details</span>
                                            </a>
                                            <a href="{{ route('admin.hotels.policies.edit', [$hotel, $policy]) }}"
                                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                                <span>✏️</span>
                                                <span>Edit Policy</span>
                                            </a>
                                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                            <form action="{{ route('admin.hotels.policies.destroy', [$hotel, $policy]) }}"
                                                method="POST" onsubmit="return confirm('Delete this policy?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                    <span>🗑️</span>
                                                    <span>Delete Policy</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No policies added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
