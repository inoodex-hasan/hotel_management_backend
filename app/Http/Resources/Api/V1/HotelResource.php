<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thumbnailUrl = null;
        if (method_exists($this->resource, 'getFirstMediaUrl') && $this->hasMedia('photos')) {
            $thumbnailUrl = $this->getFirstMediaUrl('photos');
        }

        $minPrice = null;
        if ($this->relationLoaded('roomTypes')) {
            $minPrice = $this->roomTypes->min('base_price_per_night');
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'star_rating' => (int) $this->star_rating,
            'address' => $this->address,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'is_international' => (bool) $this->is_international,
            'status' => $this->status,
            'starting_price' => $minPrice !== null ? (float) $minPrice : null,
            'thumbnail_url' => $thumbnailUrl,
            'city' => new CityResource($this->whenLoaded('city')),
            'amenities' => HotelAmenityResource::collection($this->whenLoaded('amenities')),
        ];
    }
}
