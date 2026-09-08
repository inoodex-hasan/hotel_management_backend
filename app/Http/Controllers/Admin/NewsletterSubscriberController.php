<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of newsletter subscribers.
     */
    public function index(Request $request): View
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        if ($request->filled('search')) {
            $search = '%' . trim((string) $request->search) . '%';
            $query->where('email', 'like', $search);
        }

        $subscribers = $query->paginate(20)->withQueryString();
        $totalActive = NewsletterSubscriber::where('is_active', true)->count();

        return view('admin.newsletter.index', compact('subscribers', 'totalActive'));
    }

    /**
     * Toggle active/inactive status of a subscriber.
     */
    public function toggleStatus(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);

        return back()->with('success', 'Subscriber status updated.');
    }

    /**
     * Remove the specified subscriber.
     */
    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')->with('success', 'Subscriber deleted successfully.');
    }
}
