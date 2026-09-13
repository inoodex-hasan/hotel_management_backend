<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AboutResource;
use App\Models\AboutContent;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    /**
     * Get about content for home section and about page.
     */
    public function index(Request $request): JsonResponse
    {
        $hotelSlug = $request->get('hotel_slug', 'default');
        $cacheKey = "api.about.{$hotelSlug}";

        $about = Cache::remember($cacheKey, 3600, function () use ($request) {
            $hotelId = null;
            if ($request->filled('hotel_slug')) {
                $hotel = Hotel::where('slug', $request->hotel_slug)->first();
                $hotelId = $hotel?->id;
            }

            return AboutContent::where('hotel_id', $hotelId)->first()
                ?? AboutContent::first();
        });

        if (!$about) {
            return response()->json(['data' => null]);
        }

        return (new AboutResource($about))->response();
    }
}
