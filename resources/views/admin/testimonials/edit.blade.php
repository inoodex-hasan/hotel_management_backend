@extends('admin.layouts.master')

@section('title', 'Edit Testimonial')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <ul class="flex items-center space-x-2 rtl:space-x-reverse text-xs text-slate-400 mb-1">
                <li><a href="{{ route('tyro-dashboard.index') }}" class="hover:underline">Dashboard</a></li>
                <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2"><a href="{{ route('admin.testimonials.index') }}" class="hover:underline">Testimonials</a></li>
                <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2"><span>Edit {{ $testimonial->guest_name }}</span></li>
            </ul>
            <h2 class="text-xl font-semibold uppercase">Edit Guest Review</h2>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">
            &larr; Back to Testimonials
        </a>
    </div>

    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.testimonials._form')
    </form>
@endsection
