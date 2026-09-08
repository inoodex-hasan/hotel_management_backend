@extends('admin.layouts.master')

@section('title', 'Guest Inquiries')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Guest Inquiries</h2>
            <p class="text-xs text-slate-400 mt-1">Manage contact inquiries submitted from the website</p>
        </div>
        @if($unreadCount > 0)
            <span class="badge bg-danger/10 text-danger border border-danger/20 font-bold px-3 py-1.5 rounded-xl">
                {{ $unreadCount }} Unread Message(s)
            </span>
        @endif
    </div>

    <!-- Filter & Search Panel -->
    <div class="panel mt-6">
        <form method="GET" action="{{ route('admin.inquiries.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-bold text-white-dark uppercase">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, Subject..." class="form-input" />
            </div>
            <div>
                <label class="text-xs font-bold text-white-dark uppercase">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread Only</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read Only</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary w-full">Filter</button>
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Messages List -->
    <div class="panel mt-6">
        <div class="table-responsive">
            <table class="table-striped table-hover w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 font-bold text-white-dark">
                        <th class="py-3 px-4 text-left">Sender</th>
                        <th class="py-3 px-4 text-left">Subject</th>
                        <th class="py-3 px-4 text-left">Message Snippet</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Received At</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50 {{ !$msg->is_read ? 'font-semibold bg-primary/5' : '' }}">
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $msg->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $msg->email }}</span>
                                    @if($msg->phone)
                                        <span class="text-xs text-slate-400">{{ $msg->phone }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                {{ $msg->subject ?: 'No subject' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate">
                                {{ Str::limit($msg->message, 80) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($msg->is_read)
                                    <span class="badge badge-outline-success text-xs">Read</span>
                                @else
                                    <span class="badge bg-danger/10 text-danger border border-danger/20 text-xs font-bold">New</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-400">
                                {{ $msg->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div x-data="actionDropdown" class="inline-block text-center">
                                    <button type="button" x-ref="btn"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 hover:text-primary dark:text-slate-400 dark:hover:bg-dark-light/20 transition duration-150 focus:outline-none">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                            <circle cx="12" cy="5" r="2" fill="currentColor"/>
                                            <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                            <circle cx="12" cy="19" r="2" fill="currentColor"/>
                                        </svg>
                                    </button>
                                    <div x-ref="menu" class="hidden w-44 rounded-xl bg-white p-1.5 shadow-2xl border border-slate-200 dark:border-slate-700 dark:bg-[#1b2e4b] text-slate-800 dark:text-slate-200 text-left">
                                        <a href="{{ route('admin.inquiries.show', $msg) }}"
                                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-100 hover:text-primary dark:text-slate-200 dark:hover:bg-dark-light/20">
                                            <span>👁️</span>
                                            <span>View & Reply</span>
                                        </a>
                                        <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                        <form action="{{ route('admin.inquiries.destroy', $msg) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                <span>🗑️</span>
                                                <span>Delete Inquiry</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400 font-semibold">
                                No guest inquiries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>
@endsection
