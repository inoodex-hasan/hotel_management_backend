@extends('admin.layouts.master')

@section('title', 'Upload Photo — '.$hotel->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Upload Photo</h2>
            <p class="text-sm text-white-dark">{{ $hotel->name }}</p>
        </div>
        <a href="{{ route('admin.hotels.photos.index', $hotel) }}" class="btn btn-outline-secondary">&larr; Back to Photos</a>
    </div>

    <div class="panel mt-6">
        @if($errors->any())
            <div class="alert alert-danger mb-6">
                <ul class="list-disc pl-5 text-red-500 font-semibold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.hotels.photos.store', $hotel) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="photos">Select Photos <span class="text-danger">*</span></label>
                    <input type="file" name="photos[]" id="photos" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" required accept="image/*" multiple />
                    <p class="text-xs text-white-dark mt-2">You can select multiple photos at once. Maximum 4MB per photo.</p>
                </div>
                <div class="form-group">
                    <label for="caption">Caption</label>
                    <input type="text" name="caption" id="caption" class="form-input" maxlength="200" placeholder="e.g. Lobby View" />
                    <p class="text-xs text-white-dark mt-1">Applies to all uploaded photos.</p>
                </div>
                <div class="form-group">
                    <label for="sort_order">Sort Order (Starting)</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" value="0" />
                    <p class="text-xs text-white-dark mt-1">Photos will be ordered sequentially starting from this value.</p>
                </div>
                <div class="form-group md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_cover" id="is_cover" value="1" class="form-checkbox" />
                    <label for="is_cover" class="!mb-0 font-semibold">Set first photo as Hotel Cover Photo</label>
                </div>
            </div>
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary">Upload Photos</button>
                <a href="{{ route('admin.hotels.photos.index', $hotel) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
