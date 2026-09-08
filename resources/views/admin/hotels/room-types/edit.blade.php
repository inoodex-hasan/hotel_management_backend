@extends('admin.layouts.master')

@section('title', 'Edit room type')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit room type</h2>
        <a href="{{ route('admin.hotels.room-types.index', $hotel) }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="panel mt-6">
        <p class="mb-4 text-sm">{{ $hotel->name }}</p>
        <form action="{{ route('admin.hotels.room-types.update', [$hotel, $roomType]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.hotels.room-types._form', ['roomType' => $roomType])
            <div class="mt-8">
                <button type="submit" class="btn btn-primary px-10">Update</button>
            </div>
        </form>
    </div>
@endsection
