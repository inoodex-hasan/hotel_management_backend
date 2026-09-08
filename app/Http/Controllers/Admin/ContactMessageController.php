<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact inquiries.
     */
    public function index(Request $request): View
    {
        $query = ContactMessage::latest();

        if ($request->filled('status')) {
            $isRead = $request->status === 'read';
            $query->where('is_read', $isRead);
        }

        if ($request->filled('search')) {
            $search = '%' . trim((string) $request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('phone', 'like', $search)
                    ->orWhere('subject', 'like', $search)
                    ->orWhere('message', 'like', $search);
            });
        }

        $messages = $query->paginate(15)->withQueryString();
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.inquiries.index', compact('messages', 'unreadCount'));
    }

    /**
     * Display the specified inquiry.
     */
    public function show(ContactMessage $inquiry): View
    {
        if (!$inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }

        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Toggle read/unread state.
     */
    public function toggleRead(ContactMessage $inquiry): RedirectResponse
    {
        $inquiry->update(['is_read' => !$inquiry->is_read]);

        return back()->with('success', 'Inquiry status updated.');
    }

    /**
     * Remove the specified inquiry.
     */
    public function destroy(ContactMessage $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }
}
