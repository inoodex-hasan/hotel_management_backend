@extends('admin.layouts.master')

@section('title', 'Newsletter Subscribers')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Newsletter Subscribers</h2>
            <p class="text-xs text-slate-400 mt-1">Manage guest email subscriptions captured from the website</p>
        </div>
        <div>
            <span class="badge bg-success/10 text-success border border-success/20 font-bold px-3 py-1.5 rounded-xl">
                {{ $totalActive }} Active Subscriber(s)
            </span>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="panel mt-6">
        <form method="GET" action="{{ route('admin.newsletter.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-bold text-white-dark uppercase">Search Email</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="subscriber@example.com..." class="form-input" />
            </div>
            <div>
                <label class="text-xs font-bold text-white-dark uppercase">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Subscribers</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary w-full">Filter</button>
                <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Subscribers List -->
    <div class="panel mt-6">
        <div class="table-responsive">
            <table class="table-striped table-hover w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 font-bold text-white-dark">
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Email Address</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Subscribed On</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $index => $sub)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <td class="py-3.5 px-4 text-slate-400">
                                {{ $subscribers->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                {{ $sub->email }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($sub->is_active)
                                    <span class="badge badge-outline-success text-xs">Active</span>
                                @else
                                    <span class="badge badge-outline-danger text-xs">Inactive</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-400">
                                {{ $sub->created_at->format('M d, Y H:i') }}
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
                                        <form action="{{ route('admin.newsletter.toggle-status', $sub) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold {{ $sub->is_active ? 'text-warning hover:bg-warning/10' : 'text-success hover:bg-success/10' }}">
                                                <span>{{ $sub->is_active ? '⏸️' : '▶️' }}</span>
                                                <span>{{ $sub->is_active ? 'Deactivate' : 'Activate' }}</span>
                                            </button>
                                        </form>
                                        <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                                        <form action="{{ route('admin.newsletter.destroy', $sub) }}" method="POST" onsubmit="return confirm('Delete this subscriber?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-danger hover:bg-danger/10">
                                                <span>🗑️</span>
                                                <span>Delete Subscriber</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-slate-400 font-semibold">
                                No newsletter subscribers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $subscribers->links() }}
        </div>
    </div>
@endsection
