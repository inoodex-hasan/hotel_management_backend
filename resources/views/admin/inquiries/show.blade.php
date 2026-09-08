@extends('admin.layouts.master')

@section('title', 'Inquiry Details - ' . $inquiry->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Inquiry from {{ $inquiry->name }}</h2>
            <p class="text-xs text-slate-400 mt-1">Received on {{ $inquiry->created_at->format('F d, Y \a\t H:i') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-primary">Back to Inquiries</a>
            <form action="{{ route('admin.inquiries.toggle-read', $inquiry) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $inquiry->is_read ? 'btn-outline-warning' : 'btn-outline-success' }}">
                    Mark as {{ $inquiry->is_read ? 'Unread' : 'Read' }}
                </button>
            </form>
            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Permanently delete this inquiry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Message Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="panel">
                <div class="mb-4 pb-4 border-b border-slate-100">
                    <span class="text-xs font-bold text-white-dark uppercase">Subject</span>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mt-1">{{ $inquiry->subject ?: 'General Inquiry' }}</h3>
                </div>

                <div>
                    <span class="text-xs font-bold text-white-dark uppercase">Message Content</span>
                    <div class="mt-3 p-5 bg-slate-50 dark:bg-dark-light/10 rounded-xl leading-relaxed whitespace-pre-wrap text-slate-700 dark:text-slate-200">
                        {{ $inquiry->message }}
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-3">
                    <a href="mailto:{{ $inquiry->email }}?subject={{ urlencode('Re: ' . ($inquiry->subject ?: 'Your inquiry at Azura')) }}" class="btn btn-primary">
                        Reply via Email
                    </a>
                    @if($inquiry->phone)
                        <a href="tel:{{ $inquiry->phone }}" class="btn btn-outline-info">
                            Call Sender
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sender Info -->
        <div class="space-y-6">
            <div class="panel">
                <h3 class="text-md font-bold mb-4">Sender Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-white-dark uppercase">Name</span>
                        <span class="font-semibold text-slate-800 dark:text-white mt-0.5">{{ $inquiry->name }}</span>
                    </div>
                    <div class="flex flex-col pt-2">
                        <span class="text-xs font-bold text-white-dark uppercase">Email</span>
                        <a href="mailto:{{ $inquiry->email }}" class="text-primary hover:underline mt-0.5">{{ $inquiry->email }}</a>
                    </div>
                    <div class="flex flex-col pt-2">
                        <span class="text-xs font-bold text-white-dark uppercase">Phone</span>
                        <span class="mt-0.5">{{ $inquiry->phone ?: 'Not provided' }}</span>
                    </div>
                    <div class="flex flex-col pt-2">
                        <span class="text-xs font-bold text-white-dark uppercase">Status</span>
                        <span class="mt-0.5">
                            @if($inquiry->is_read)
                                <span class="badge badge-outline-success">Read</span>
                            @else
                                <span class="badge bg-danger/10 text-danger border border-danger/20 font-bold">Unread</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
