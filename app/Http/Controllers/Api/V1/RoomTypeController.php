<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use Illuminate\Support\Facades\Cache;

class RoomTypeController extends Controller
{
    /**
     * Get room types list with optional hotel and price filters
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $queryString = md5(json_encode($request->all()));
        $cacheKey = "api.room_types.list.{$queryString}";

        $roomTypes = Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = RoomType::query()->with(['hotel.media', 'media']);

            if ($request->filled('hotel_id')) {
                $query->where('hotel_id', $request->hotel_id);
            }

            if ($request->filled('hotel_slug')) {
                $query->whereHas('hotel', function ($q) use ($request) {
                    $q->where('slug', $request->hotel_slug);
                });
            }

            if ($request->filled('tag')) {
                $query->where('tag', $request->tag);
            }

            if ($request->filled('min_price')) {
                $query->where('base_price_per_night', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('base_price_per_night', '<=', $request->max_price);
            }

            if ($request->filled('guests')) {
                $query->where('capacity_adults', '>=', $request->guests);
            }

            $sort = $request->get('sort', 'price_asc');
            match ($sort) {
                'price_desc' => $query->orderByDesc('base_price_per_night'),
                'rating_desc' => $query->orderByDesc('stars'),
                default => $query->orderBy('base_price_per_night'),
            };

            $perPage = min((int) $request->get('per_page', 50), 100);
            return $query->paginate($perPage);
        });

        return RoomTypeResource::collection($roomTypes);
    }

    /**
     * Get single room type by slug or ID
     */
    public function show(string $slugOrId): JsonResponse|RoomTypeResource
    {
        $cacheKey = "api.room_types.item.{$slugOrId}";

        $roomType = Cache::remember($cacheKey, 3600, function () use ($slugOrId) {
            return RoomType::with(['hotel.media', 'media'])
                ->where('slug', $slugOrId)
                ->orWhere('id', is_numeric($slugOrId) ? $slugOrId : 0)
                ->first();
        });

        if (!$roomType) {
            return response()->json([
                'message' => 'Room type not found.',
            ], 404);
        }

        return new RoomTypeResource($roomType);
    }
}
