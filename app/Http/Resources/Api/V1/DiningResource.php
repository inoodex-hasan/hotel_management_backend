<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiningResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'title' => $this->name,
            'label' => $this->label,
            'description' => $this->description,
            'image' => $imageUrl,
            'location' => $this->location,
            'serves' => $this->serves,
            'phone' => $this->phone,
            'hours' => $this->hours,
            'details' => [
                'location' => $this->location,
                'serves' => $this->serves,
                'phone' => $this->phone,
                'hours' => $this->hours,
            ],
            'features' => $this->features ?? [],
            'sort_order' => (int) $this->sort_order,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
