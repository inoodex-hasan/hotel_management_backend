<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $photos = [];
        if (method_exists($this->resource, 'getMedia') && $this->hasMedia('photos')) {
            $photos = $this->getMedia('photos')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                ];
            })->all();
        }

        $minPrice = null;
        if ($this->relationLoaded('roomTypes')) {
            $minPrice = $this->roomTypes->min('base_price_per_night');
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'star_rating' => (int) $this->star_rating,
            'address' => $this->address,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'is_international' => (bool) $this->is_international,
            'status' => $this->status,
            'starting_price' => $minPrice !== null ? (float) $minPrice : null,
            'seo' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
            ],
            'photos' => $photos,
            'city' => new CityResource($this->whenLoaded('city')),
            'room_types' => RoomTypeResource::collection($this->whenLoaded('roomTypes')),
            'amenities' => HotelAmenityResource::collection($this->whenLoaded('amenities')),
            'policies' => HotelPolicyResource::collection($this->whenLoaded('policies')),
        ];
    }
}
