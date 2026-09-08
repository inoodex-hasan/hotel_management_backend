<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomTypeOverviewController extends Controller
{
    /**
     * Display a global listing of all room types with photo previews and hotel tags.
     */
    public function index(Request $request): View
    {
        $query = RoomType::with('hotel')->latest();

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->filled('search')) {
            $s = '%' . trim((string) $request->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('slug', 'like', $s)
                    ->orWhere('bed_type', 'like', $s);
            });
        }

        $roomTypes = $query->paginate(15)->withQueryString();
        $hotels = Hotel::orderBy('name')->get();

        return view('admin.hotels.room-types.all', compact('roomTypes', 'hotels'));
    }
}
