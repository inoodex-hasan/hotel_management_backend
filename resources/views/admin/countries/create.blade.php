@extends('admin.layouts.master')

@section('title', 'Add Country')

@section('content')
    <div>
        <h2 class="text-xl font-semibold uppercase">Add Country</h2>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.countries.store') }}" method="POST">
            @csrf
            @include('admin.countries._form', ['country' => null])
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary">Create Country</button>
                <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
