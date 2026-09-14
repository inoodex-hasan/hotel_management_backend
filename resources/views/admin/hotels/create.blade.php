@extends('admin.layouts.master')

@section('title', 'New hotel')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New hotel</h2>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">&larr; Back to Hotels</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hotels.store') }}" method="POST">
            @csrf
            @include('admin.hotels._form', ['hotel' => null, 'cities' => $cities])
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save</button>
            </div>
        </form>
    </div>
@endsection
