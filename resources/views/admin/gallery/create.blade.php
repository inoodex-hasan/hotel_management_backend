@extends('admin.layouts.master')

@section('title', 'Add Gallery Photo')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <ul class="flex items-center space-x-2 rtl:space-x-reverse text-xs text-slate-400 mb-1">
                <li><a href="{{ route('tyro-dashboard.index') }}" class="hover:underline">Dashboard</a></li>
                <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2"><a href="{{ route('admin.gallery.index') }}" class="hover:underline">Gallery</a></li>
                <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2"><span>Add New</span></li>
            </ul>
            <h2 class="text-xl font-semibold uppercase">Add New Gallery Photo</h2>
        </div>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary btn-sm">
            &larr; Back to Gallery
        </a>
    </div>

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.gallery._form')
    </form>
@endsection
