<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CityResource;
use App\Http\Resources\Api\V1\HotelDetailResource;
use App\Http\Resources\Api\V1\HotelResource;
use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HotelController extends Controller
{
    /**
     * Get paginated active hotels.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->input('per_page', 12), 50);

        $query = Hotel::where('status', 'active')
            ->with(['city.country', 'media', 'amenities', 'roomTypes' => function ($q) {
                $q->orderBy('base_price_per_night', 'asc');
            }]);

        $this->applySorting($query, $request->input('sort', 'popularity'));

        $hotels = $query->paginate($perPage);

        return HotelResource::collection($hotels);
    }

    /**
     * Get featured hotels for home page.
     */
    public function featured(Request $request): AnonymousResourceCollection
    {
        $limit = min((int) $request->input('limit', 6), 20);

        $hotels = Hotel::where('status', 'active')
            ->with(['city.country', 'media', 'amenities', 'roomTypes' => function ($q) {
                $q->orderBy('base_price_per_night', 'asc');
            }])
            ->inRandomOrder()
            ->take($limit)
            ->get();

        return HotelResource::collection($hotels);
    }

    /**
     * Advanced Hotel Search & Filter.
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'location' => ['nullable', 'string', 'max:120'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date', 'after:check_in'],
            'rooms' => ['nullable', 'integer', 'min:1'],
            'guests' => ['nullable', 'integer', 'min:1'],
            'sort' => ['nullable', 'in:popularity,price_low,price_high,rating_high'],
            'price_range' => ['nullable'],
            'rating' => ['nullable'],
            'amenities' => ['nullable'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $perPage = min((int) $request->input('per_page', 12), 50);

        $query = Hotel::where('status', 'active')
            ->with([
                'city.country',
                'media',
                'amenities',
                'roomTypes' => function ($q) {
                    $q->orderBy('base_price_per_night', 'asc');
                },
            ]);

        // Location filter
        if ($request->filled('location')) {
            $location = trim((string) $request->input('location'));
            $query->where(function ($q) use ($location) {
                $q->where('name', 'like', '%' . $location . '%')
                    ->orWhereHas('city', function ($cityQ) use ($location) {
                        $cityQ->where('name', 'like', '%' . $location . '%')
                            ->orWhereHas('country', function ($countryQ) use ($location) {
                                $countryQ->where('name', 'like', '%' . $location . '%');
                            });
                    });
            });
        }

        // Rooms count availability
        if ($request->filled('rooms')) {
            $rooms = (int) $request->input('rooms');
            $query->whereHas('roomTypes', function ($q) use ($rooms) {
                $q->where('available_rooms', '>=', $rooms);
            });
        }

        // Price range filters
        $priceRanges = $request->input('price_range', []);
        if (is_string($priceRanges)) {
            $priceRanges = explode(',', $priceRanges);
        }
        $priceRanges = array_filter((array) $priceRanges);

        if (!empty($priceRanges)) {
            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    if ($range === '0-5000') {
                        $q->orWhereHas('roomTypes', fn ($rq) => $rq->whereBetween('base_price_per_night', [0, 5000]));
                    } elseif ($range === '5001-10000') {
                        $q->orWhereHas('roomTypes', fn ($rq) => $rq->whereBetween('base_price_per_night', [5001, 10000]));
                    } elseif ($range === '10001-20000') {
                        $q->orWhereHas('roomTypes', fn ($rq) => $rq->whereBetween('base_price_per_night', [10001, 20000]));
                    } elseif ($range === '20001+') {
                        $q->orWhereHas('roomTypes', fn ($rq) => $rq->where('base_price_per_night', '>=', 20001));
                    }
                }
            });
        }

        // Star rating filters
        $ratings = $request->input('rating', []);
        if (is_string($ratings)) {
            $ratings = explode(',', $ratings);
        }
        $ratings = array_map('intval', array_filter((array) $ratings));
        if (!empty($ratings)) {
            $query->whereIn('star_rating', $ratings);
        }

        // Amenities filter
        $amenities = $request->input('amenities', []);
        if (is_string($amenities)) {
            $amenities = explode(',', $amenities);
        }
        $amenities = array_filter((array) $amenities);
        if (!empty($amenities)) {
            $query->whereHas('amenities', function ($q) use ($amenities) {
                $q->whereIn('amenity_name', $amenities);
            });
        }

        $this->applySorting($query, $request->input('sort', 'popularity'));

        $hotels = $query->paginate($perPage);

        return HotelResource::collection($hotels);
    }

    /**
     * Search bar autocomplete suggestions (Hotels & Cities).
     */
    public function suggestions(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('query', $request->input('q', '')));

        $hotelsQuery = Hotel::where('status', 'active')
            ->with(['city', 'media'])
            ->orderBy('name');

        $citiesQuery = City::whereHas('hotels', function ($query) {
            $query->where('status', 'active');
        })
            ->withCount(['hotels' => fn ($query) => $query->where('status', 'active')])
            ->with(['country', 'media'])
            ->orderBy('name');

        if ($q !== '') {
            $hotelsQuery->where('name', 'like', '%' . $q . '%');
            $citiesQuery->where('name', 'like', '%' . $q . '%');
        }

        $hotels = $hotelsQuery->take(8)->get();
        $cities = $citiesQuery->take(8)->get();

        return response()->json([
            'data' => [
                'hotels' => HotelResource::collection($hotels),
                'cities' => CityResource::collection($cities),
            ],
        ]);
    }

    /**
     * Single Hotel Details.
     */
    public function show(string $slug): HotelDetailResource
    {
        $hotel = Hotel::where('slug', $slug)
            ->orWhere('id', is_numeric($slug) ? (int) $slug : 0)
            ->where('status', 'active')
            ->with([
                'city.country',
                'media',
                'amenities',
                'policies',
                'roomTypes' => function ($q) {
                    $q->orderBy('base_price_per_night', 'asc');
                },
            ])
            ->firstOrFail();

        return new HotelDetailResource($hotel);
    }

    /**
     * Apply sorting to query.
     */
    protected function applySorting($query, ?string $sort): void
    {
        if ($sort === 'price_low') {
            $query->orderByRaw('(SELECT MIN(base_price_per_night) FROM room_types WHERE room_types.hotel_id = hotels.id) ASC');
        } elseif ($sort === 'price_high') {
            $query->orderByRaw('(SELECT MIN(base_price_per_night) FROM room_types WHERE room_types.hotel_id = hotels.id) DESC');
        } elseif ($sort === 'rating_high') {
            $query->orderByDesc('star_rating');
        } else {
            $query->orderByDesc('star_rating')->orderBy('name');
        }
    }
}
