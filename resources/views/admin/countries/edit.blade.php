@extends('admin.layouts.master')

@section('title', 'Edit Country')

@section('content')
    <div>
        <h2 class="text-xl font-semibold uppercase">Edit Country: {{ $country->name }}</h2>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.countries.update', $country) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.countries._form')
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary">Update Country</button>
                <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
