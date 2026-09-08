@extends('admin.layouts.master')

@section('title', 'Add Dining Venue')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold uppercase">Add Dining Venue</h2>
            <p class="text-xs text-slate-400 mt-1">Create a new restaurant, bar, or dining venue for the hotel</p>
        </div>
        <a href="{{ route('admin.dining.index') }}" class="btn btn-outline-secondary">
            &larr; Back to Venues
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
        <form action="{{ route('admin.dining.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.dining._form')

            <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700/60 pt-5">
                <a href="{{ route('admin.dining.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Dining Venue</button>
            </div>
        </form>
    </div>
@endsection
