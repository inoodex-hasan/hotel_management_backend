@extends('admin.layouts.master')

@section('title', 'Edit Hero Slide — #' . $heroSlide->id)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-semibold uppercase">Edit Hero Slide</h2>
            <p class="text-xs text-slate-400 mt-1">Editing slide #{{ $heroSlide->id }} ({{ $heroSlide->title ?: 'Untitled' }})</p>
        </div>
        <div>
            <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-outline-secondary">
                &larr; Back to Hero Slides
            </a>
        </div>
    </div>

    <form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.hero-slides._form')
    </form>
@endsection
