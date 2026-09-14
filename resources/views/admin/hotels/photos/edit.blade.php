@extends('admin.layouts.master')

@section('title', 'Edit Photo — '.$hotel->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Edit Photo Details</h2>
            <p class="text-sm text-white-dark">{{ $hotel->name }}</p>
        </div>
        <a href="{{ route('admin.hotels.photos.index', $hotel) }}" class="btn btn-outline-secondary">&larr; Back to Photos</a>
    </div>

    <div class="panel mt-6">
        <div class="mb-8 flex justify-center">
            <img src="{{ $photo->getUrl() }}" class="max-h-64 rounded-lg shadow-md" alt="Current Photo">
        </div>

        <form action="{{ route('admin.hotels.photos.update', [$hotel, $photo]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="caption">Caption</label>
                    <input type="text" name="caption" id="caption" class="form-input" maxlength="200" value="{{ old('caption', $photo->getCustomProperty('caption')) }}" />
                </div>
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', $photo->order_column) }}" />
                </div>
                <div class="form-group md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_cover" id="is_cover" value="1" class="form-checkbox" {{ $photo->getCustomProperty('is_cover') ? 'checked' : '' }} />
                    <label for="is_cover" class="!mb-0 font-semibold">Set as Hotel Cover Photo</label>
                </div>
            </div>
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary">Update Details</button>
                <a href="{{ route('admin.hotels.photos.index', $hotel) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
