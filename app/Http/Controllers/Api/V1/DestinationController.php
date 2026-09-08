<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CityResource;
use App\Http\Resources\Api\V1\HotelResource;
use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DestinationController extends Controller
{
    /**
     * Get paginated destinations (cities with hotels).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->input('per_page', 12), 50);

        $destinations = City::whereHas('hotels', fn ($q) => $q->where('status', 'active'))
            ->withCount(['hotels' => fn ($q) => $q->where('status', 'active')])
            ->with(['country', 'media'])
            ->orderByDesc('hotels_count')
            ->paginate($perPage);

        return CityResource::collection($destinations);
    }

    /**
     * Get top popular destinations for home page.
     */
    public function popular(Request $request): AnonymousResourceCollection
    {
        $limit = min((int) $request->input('limit', 6), 20);

        $destinations = City::whereHas('hotels', fn ($q) => $q->where('status', 'active'))
            ->withCount(['hotels' => fn ($q) => $q->where('status', 'active')])
            ->with(['country', 'media'])
            ->orderByDesc('hotels_count')
            ->take($limit)
            ->get();

        return CityResource::collection($destinations);
    }

    /**
     * Get single destination with its active hotels.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $city = City::withCount(['hotels' => fn ($q) => $q->where('status', 'active')])
            ->with(['country', 'media'])
            ->where('id', is_numeric($id) ? (int) $id : 0)
            ->orWhere('name', $id)
            ->firstOrFail();

        $perPage = min((int) $request->input('per_page', 12), 50);

        $hotelsQuery = Hotel::where('status', 'active')
            ->where('city_id', $city->id)
            ->with(['city.country', 'media', 'amenities', 'roomTypes' => function ($q) {
                $q->orderBy('base_price_per_night', 'asc');
            }]);

        $sort = $request->input('sort', 'popularity');
        if ($sort === 'price_low') {
            $hotelsQuery->orderByRaw('(SELECT MIN(base_price_per_night) FROM room_types WHERE room_types.hotel_id = hotels.id) ASC');
        } elseif ($sort === 'price_high') {
            $hotelsQuery->orderByRaw('(SELECT MIN(base_price_per_night) FROM room_types WHERE room_types.hotel_id = hotels.id) DESC');
        } elseif ($sort === 'rating_high') {
            $hotelsQuery->orderByDesc('star_rating');
        } else {
            $hotelsQuery->orderByDesc('star_rating')->orderBy('name');
        }

        $hotels = $hotelsQuery->paginate($perPage);

        return response()->json([
            'data' => [
                'destination' => new CityResource($city),
                'hotels' => HotelResource::collection($hotels)->response()->getData(true),
            ],
        ]);
    }
}
