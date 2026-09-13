<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\HeroSlideResource;
use App\Models\HeroSlide;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use Illuminate\Support\Facades\Cache;

class HeroSlideController extends Controller
{
    /**
     * Get active hero slides.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $hotelSlug = $request->get('hotel_slug', 'all');
        $cacheKey = "api.hero_slides.{$hotelSlug}";

        $slides = Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = HeroSlide::active();

            if ($request->filled('hotel_slug')) {
                $hotel = Hotel::where('slug', $request->hotel_slug)->first();
                if ($hotel) {
                    $query->where(function ($q) use ($hotel) {
                        $q->where('hotel_id', $hotel->id)->orWhereNull('hotel_id');
                    });
                }
            }

            return $query->get();
        });

        return HeroSlideResource::collection($slides);
    }
}
