<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use Illuminate\Support\Facades\Cache;

class TestimonialController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $hotelId = $request->get('hotel_id', 'all');
        $featured = $request->boolean('featured_only') ? '1' : '0';
        $cacheKey = "api.testimonials.{$hotelId}.{$featured}";

        $testimonials = Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = Testimonial::query()->where('is_approved', true);

            if ($request->filled('hotel_id')) {
                $query->where('hotel_id', $request->hotel_id);
            }

            if ($request->boolean('featured_only')) {
                $query->where('is_featured', true);
            }

            return $query->latest()->get();
        });

        return TestimonialResource::collection($testimonials);
    }
}
