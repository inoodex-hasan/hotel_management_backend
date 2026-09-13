<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GalleryItemResource;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use Illuminate\Support\Facades\Cache;

class GalleryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $hotelId = $request->get('hotel_id', 'all');
        $cat = strtolower($request->get('category', 'all'));
        $cacheKey = "api.gallery.{$hotelId}.{$cat}";

        $items = Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = GalleryItem::query()->where('is_active', true);

            if ($request->filled('hotel_id')) {
                $query->where('hotel_id', $request->hotel_id);
            }

            if ($request->filled('category') && strtolower($request->category) !== 'all') {
                $query->where('category', $request->category);
            }

            return $query->orderBy('sort_order')->get();
        });

        return GalleryItemResource::collection($items);
    }
}
