<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AboutResource;
use App\Models\AboutContent;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Get about content for home section and about page.
     */
    public function index(Request $request): JsonResponse
    {
        $hotelId = null;
        if ($request->filled('hotel_slug')) {
            $hotel = Hotel::where('slug', $request->hotel_slug)->first();
            $hotelId = $hotel?->id;
        }

        $about = AboutContent::where('hotel_id', $hotelId)->first()
            ?? AboutContent::first();

        if (!$about) {
            return response()->json(['data' => null]);
        }

        return (new AboutResource($about))->response();
    }
}
