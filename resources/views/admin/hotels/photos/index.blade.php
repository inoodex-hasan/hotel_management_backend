@extends('admin.layouts.master')

@section('title', 'Hotel Photos — '.$hotel->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Hotel Photos</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hotels.show', $hotel) }}" class="btn btn-secondary">View Hotel</a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">All Hotels</a>
            <a href="{{ route('admin.hotels.photos.create', $hotel) }}" class="btn btn-primary">Upload Photo</a>
        </div>
    </div>

    <div class="panel mt-6">
        <p class="mb-6 text-sm text-white-dark">{{ $hotel->name }}</p>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($photos as $photo)
                <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-[#1b2e4b] dark:bg-[#0e1726]">
                    <div class="aspect-video w-full overflow-hidden">
                        <img src="{{ $photo->getUrl() }}" alt="{{ $photo->getCustomProperty('caption') }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-110">
                    </div>
                    @if($photo->getCustomProperty('is_cover'))
                        <span class="absolute top-2 left-2 rounded bg-primary px-2 py-0.5 text-[10px] font-bold text-white uppercase">Cover</span>
                    @endif
                    <div class="p-4">
                        <p class="truncate text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $photo->getCustomProperty('caption') ?: 'No caption' }}</p>
                        <div class="mt-4 flex items-center justify-between gap-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Order: {{ $photo->order_column }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.hotels.photos.edit', [$hotel, $photo]) }}" class="btn btn-xs btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.hotels.photos.destroy', [$hotel, $photo]) }}" method="POST" onsubmit="return confirm('Delete this photo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    No photos uploaded yet.
                </div>
            @endforelse
        </div>
    </div>
@endsection
