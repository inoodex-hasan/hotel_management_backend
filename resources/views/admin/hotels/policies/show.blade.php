@extends('admin.layouts.master')

@section('title', 'Policy: '.$policy->title)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">View Policy Details</h2>
            <p class="text-sm text-white-dark">{{ $hotel->name }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hotels.policies.index', $hotel) }}" class="btn btn-secondary">Back to Policies</a>
            <a href="{{ route('admin.hotels.policies.edit', [$hotel, $policy]) }}" class="btn btn-primary">Edit Policy</a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Left Information Column -->
            <div class="md:col-span-1 border-r border-slate-100 dark:border-slate-800 pr-6 space-y-4">
                <div>
                    <span class="text-xs font-bold text-white-dark uppercase block mb-1">Policy Category</span>
                    <span class="badge badge-outline-info uppercase font-bold text-xs">
                        {{ $categories[$policy->category] ?? str_replace('_', ' ', $policy->category) }}
                    </span>
                </div>

                <div>
                    <span class="text-xs font-bold text-white-dark uppercase block mb-1">Policy Title</span>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $policy->title }}</h3>
                </div>

                <div>
                    <span class="text-xs font-bold text-white-dark uppercase block mb-1">Status</span>
                    @if($policy->is_active)
                        <span class="badge badge-outline-success font-semibold">Active Policy</span>
                    @else
                        <span class="badge badge-outline-danger font-semibold">Inactive Policy</span>
                    @endif
                </div>

                <div>
                    <span class="text-xs font-bold text-white-dark uppercase block mb-1">Created At</span>
                    <span class="text-sm text-gray-700 dark:text-white-dark">{{ $policy->created_at->format('M d, Y h:i A') }}</span>
                </div>

                <div>
                    <span class="text-xs font-bold text-white-dark uppercase block mb-1">Last Updated</span>
                    <span class="text-sm text-gray-700 dark:text-white-dark">{{ $policy->updated_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            <!-- Right Content Column -->
            <div class="md:col-span-2 space-y-4">
                <div>
                    <span class="text-xs font-bold text-white-dark uppercase block mb-2">Policy Description</span>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800 p-6 bg-slate-50/30 dark:bg-slate-900/10 prose dark:prose-invert max-w-none">
                        {!! $policy->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
