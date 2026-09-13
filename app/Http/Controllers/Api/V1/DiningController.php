<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DiningResource;
use App\Models\Dining;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use Illuminate\Support\Facades\Cache;

class DiningController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $hotelId = $request->get('hotel_id', 'all');
        $cacheKey = "api.dining.{$hotelId}";

        $dining = Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = Dining::query()->where('is_active', true);

            if ($request->filled('hotel_id')) {
                $query->where('hotel_id', $request->hotel_id);
            }

            return $query->orderBy('sort_order')->get();
        });

        return DiningResource::collection($dining);
    }

    public function show(string $slugOrId): JsonResponse|DiningResource
    {
        $cacheKey = "api.dining.item.{$slugOrId}";

        $dining = Cache::remember($cacheKey, 3600, function () use ($slugOrId) {
            return Dining::where('is_active', true)
                ->where(function ($q) use ($slugOrId) {
                    $q->where('slug', $slugOrId)
                        ->orWhere('id', is_numeric($slugOrId) ? $slugOrId : 0);
                })
                ->first();
        });

        if (!$dining) {
            return response()->json([
                'message' => 'Dining venue not found.',
            ], 404);
        }

        return new DiningResource($dining);
    }
}
