<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FacilityResource;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FacilityController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Facility::query()->where('is_active', true);

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $facilities = $query->orderBy('sort_order')->get();

        return FacilityResource::collection($facilities);
    }
}
