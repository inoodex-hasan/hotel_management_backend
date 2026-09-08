<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $avatarUrl = $this->avatar;
        if (method_exists($this->resource, 'getFirstMediaUrl') && $mediaUrl = $this->resource->getFirstMediaUrl('avatar')) {
            $avatarUrl = $mediaUrl;
        }

        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'name' => $this->guest_name,
            'location' => $this->location,
            'stay' => $this->stay_room,
            'rating' => (int) $this->rating,
            'quote' => $this->quote,
            'avatar' => $avatarUrl,
            'is_featured' => (bool) $this->is_featured,
        ];
    }
}
