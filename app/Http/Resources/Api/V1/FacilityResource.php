<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image;
        if (empty($imageUrl) && method_exists($this->resource, 'getFirstMediaUrl') && $mediaUrl = $this->resource->getFirstMediaUrl('image')) {
            $imageUrl = $mediaUrl;
        }

        if ($imageUrl && (str_starts_with($imageUrl, '/storage/') || str_starts_with($imageUrl, 'storage/'))) {
            $imageUrl = url(ltrim($imageUrl, '/'));
        }

        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'icon' => $this->icon,
            'image' => $imageUrl,
            'features' => $this->features ?? [],
            'opening_hours' => $this->opening_hours,
            'sort_order' => (int) $this->sort_order,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
