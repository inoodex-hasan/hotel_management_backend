@extends('admin.layouts.master')

@section('title', 'Add Facility')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold uppercase">Add Facility</h2>
            <p class="text-xs text-slate-400 mt-1">Create a new guest amenity or wellness facility</p>
        </div>
        <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-secondary">
            &larr; Back to Facilities
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.facilities._form')

            <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700/60 pt-5">
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Facility</button>
            </div>
        </form>
    </div>
@endsection
