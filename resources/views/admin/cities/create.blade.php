@extends('admin.layouts.master')

@section('title', 'Add City')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add City</h2>
        <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-secondary">&larr; Back to Cities</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.cities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.cities._form', ['city' => null])
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary">Create City</button>
                <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
