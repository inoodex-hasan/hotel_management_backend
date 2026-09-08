<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DiningResource;
use App\Models\Dining;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DiningController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Dining::query()->where('is_active', true);

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $dining = $query->orderBy('sort_order')->get();

        return DiningResource::collection($dining);
    }

    public function show(string $slugOrId): JsonResponse|DiningResource
    {
        $dining = Dining::where('is_active', true)
            ->where(function ($q) use ($slugOrId) {
                $q->where('slug', $slugOrId)
                    ->orWhere('id', is_numeric($slugOrId) ? $slugOrId : 0);
            })
            ->first();

        if (!$dining) {
            return response()->json([
                'message' => 'Dining venue not found.',
            ], 404);
        }

        return new DiningResource($dining);
    }
}
