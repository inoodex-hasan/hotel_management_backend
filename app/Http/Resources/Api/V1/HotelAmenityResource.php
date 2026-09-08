<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelAmenityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amenity_name' => $this->amenity_name,
            'icon' => $this->icon,
            'description' => $this->description,
            'is_featured' => (bool) $this->is_featured,
        ];
    }
}
