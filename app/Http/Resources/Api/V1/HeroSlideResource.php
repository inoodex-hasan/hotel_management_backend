<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroSlideResource extends JsonResource
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
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'badge_text' => $this->badge_text,
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,
            'image' => $this->image_full_url,
            'image_url' => $this->image_full_url,
            'order' => (int) $this->order,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
