@extends('admin.layouts.master')

@section('title', $hotel->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold">{{ $hotel->name }}</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">&larr; Back to Hotels</a>
            <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.hotels.room-types.index', $hotel) }}" class="btn btn-outline-info">Room Types</a>
            <a href="{{ route('admin.hotels.amenities.index', $hotel) }}" class="btn btn-outline-success">Amenities</a>
            <a href="{{ route('admin.hotels.photos.index', $hotel) }}" class="btn btn-outline-warning">Photos</a>
            <a href="{{ route('admin.hotels.policies.index', $hotel) }}" class="btn btn-outline-danger">Policies</a>
        </div>
    </div>

    <div class="panel mt-6 grid gap-6 md:grid-cols-2">
        <div>
            <h3 class="mb-2 text-lg font-semibold">Details</h3>
            <dl class="space-y-2 text-sm">
                <dt class="text-white-dark">City</dt>
                <dd>{{ $hotel->city->name }}, {{ $hotel->city->country->name }}</dd>
                <dt class="text-white-dark">Stars</dt>
                <dd>{{ $hotel->star_rating }}</dd>
                <dt class="text-white-dark">Status</dt>
                <dd class="capitalize">{{ $hotel->status }}</dd>
                <dt class="text-white-dark">Address</dt>
                <dd>{{ $hotel->address }}</dd>
                @if ($hotel->description)
                    <dt class="text-white-dark">Description</dt>
                    <dd class="prose dark:prose-invert max-w-none text-sm">{!! $hotel->description !!}</dd>
                @endif
            </dl>
        </div>
        <div>
            <h3 class="mb-2 text-lg font-semibold">Room Types ({{ $hotel->roomTypes->count() }})</h3>
            <ul class="list-inside list-disc text-sm space-y-1">
                @forelse ($hotel->roomTypes as $rt)
                    <li>
                        <span class="font-semibold">{{ $rt->name }}</span> — 
                        {{ $rt->available_rooms }}/{{ $rt->total_rooms }} Available · 
                        <span class="text-primary font-bold">{{ number_format((float) $rt->base_price_per_night, 2) }}</span>
                    </li>
                @empty
                    <li>None yet.</li>
                @endforelse
            </ul>
        </div>

        @if ($hotel->policies->count() > 0)
            <div class="md:col-span-2 border-t border-slate-100 dark:border-slate-800 pt-6">
                <h3 class="mb-4 text-lg font-semibold flex items-center gap-2 text-slate-800 dark:text-white">
                    Policies ({{ $hotel->policies->count() }})
                </h3>
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($hotel->policies as $policy)
                        <div class="rounded-2xl border border-slate-100 dark:border-slate-800 p-5 bg-slate-50/20 dark:bg-slate-900/10 space-y-3">
                            <div class="flex items-center justify-between gap-4">
                                <h4 class="font-bold text-gray-800 dark:text-white-dark text-base">{{ $policy->title }}</h4>
                                <span class="text-[10px] px-2.5 py-0.5 rounded-full capitalize font-semibold tracking-wide {{ $policy->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                    {{ str_replace('_', ' ', $policy->category) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-white-dark prose dark:prose-invert max-w-none">
                                {!! $policy->description !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
