<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HotelAmenity;
use Illuminate\Http\JsonResponse;

class AmenityController extends Controller
{
    /**
     * Get list of distinct amenities for search filters.
     */
    public function index(): JsonResponse
    {
        $amenities = HotelAmenity::query()
            ->select('amenity_name', 'icon')
            ->whereNotNull('amenity_name')
            ->groupBy('amenity_name', 'icon')
            ->orderBy('amenity_name')
            ->get();

        return response()->json([
            'data' => $amenities,
        ]);
    }
}
