@extends('admin.layouts.master')

@section('title', 'Edit Facility: ' . $facility->title)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold uppercase">Edit Facility</h2>
            <p class="text-xs text-slate-400 mt-1">Updating details for <span class="font-semibold text-primary">{{ $facility->title }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-secondary">
                &larr; Back to Facilities
            </a>
        </div>
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
        <form action="{{ route('admin.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.facilities._form')

            <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700/60 pt-5">
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Facility</button>
            </div>
        </form>
    </div>
@endsection
