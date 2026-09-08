@extends('admin.layouts.master')

@section('title', 'Add Hero Slide')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-semibold uppercase">Add New Hero Slide</h2>
            <p class="text-xs text-slate-400 mt-1">Add a new slide for the frontend home page banner</p>
        </div>
        <div>
            <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-outline-secondary">
                &larr; Back to Hero Slides
            </a>
        </div>
    </div>

    <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.hero-slides._form')
    </form>
@endsection
