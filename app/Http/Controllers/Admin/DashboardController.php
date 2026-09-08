<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Models\Privilege;

class DashboardController extends Controller
{
    public function index()
    {
        $userModel = config('tyro-dashboard.user_model', 'App\\Models\\User');

        $stats = [
            'total_users' => class_exists($userModel) ? $userModel::count() : 0,
            'total_roles' => class_exists(Role::class) ? Role::count() : 0,
            'total_privileges' => class_exists(Privilege::class) ? Privilege::count() : 0,
            'total_hotels' => \App\Models\Hotel::count(),
            'total_bookings' => \App\Models\Booking::count(),
            'total_cities' => \App\Models\City::count(),
            'total_revenue' => \App\Models\Booking::sum('net_amount'),
            'total_inquiries' => \App\Models\ContactMessage::count(),
            'unread_inquiries' => \App\Models\ContactMessage::where('is_read', false)->count(),
            'total_subscribers' => \App\Models\NewsletterSubscriber::count(),
        ];

        $recentBookings = \App\Models\Booking::with(['hotelBooking.hotel', 'passengers', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
