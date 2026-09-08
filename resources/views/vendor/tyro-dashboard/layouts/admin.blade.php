@extends('admin.layouts.master')

@section('title')
    @yield('title', 'Admin Dashboard')
@endsection

@section('content')
    <!-- Content wrapper scoped for Tyro components -->
    <div class="tyro-content animate__animated">
        @include('tyro-dashboard::partials.flash-messages')
        @yield('content')
    </div>
@endsection

@push('styles')
    @include('tyro-dashboard::partials.styles')
    <style>
        /* Component-level tweaks for Tyro within VRISTO */
        .tyro-content {
            font-family: 'Nunito', sans-serif;
        }

        /* Ensure Tyro components inherit VRISTO's base font */
        .tyro-content [class*="stat-"],
        .tyro-content .card-title,
        .tyro-content .table,
        .tyro-content .btn,
        .tyro-content .form-label,
        .tyro-content .form-input,
        .tyro-content .page-title {
            font-family: 'Nunito', sans-serif !important;
        }

        /* Sync Tyro cards with VRISTO dashboard panel style */
        .tyro-content .card {
            background-color: #fff !important;
            border-radius: 6px !important;
            box-shadow: 0 0 10px 0 rgba(94, 92, 154, 0.1) !important;
            border: 1px solid #e0e6ed !important;
        }

        .tyro-content .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid #e0e6ed !important;
        }

        /* Dark mode consistency */
        .dark .tyro-content .card {
            background-color: #0e1726 !important;
            border-color: #191e3a !important;
            color: #e0e6ed !important;
            box-shadow: none !important;
        }

        .dark .tyro-content .card-header {
            border-bottom-color: #191e3a !important;
        }

        .dark .tyro-content .table th {
            background-color: #1a2941 !important;
            border-bottom-color: #191e3a !important;
            color: #888ea8 !important;
        }

        .dark .tyro-content .table td {
            border-bottom-color: #191e3a !important;
            color: #e0e6ed !important;
        }

        .dark .tyro-content .form-label {
            color: #e0e6ed !important;
        }

        .dark .tyro-content .form-input {
            background-color: #1b2e4b !important;
            border-color: #253b5c !important;
            color: #888ea8 !important;
        }

        /* Adjust page header spacing */
        .tyro-content .page-header {
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@push('scripts')
    @include('tyro-dashboard::partials.scripts')
@endpush