<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutResource extends JsonResource
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
            'hotel_id' => $this->hotel_id,
            'eyebrow' => $this->eyebrow,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'feature_1_title' => $this->feature_1_title,
            'feature_1_subtitle' => $this->feature_1_subtitle,
            'feature_2_title' => $this->feature_2_title,
            'feature_2_subtitle' => $this->feature_2_subtitle,
            'main_image' => $this->main_image_full_url,
            'main_image_url' => $this->main_image_full_url,
            'sub_image' => $this->sub_image_full_url,
            'sub_image_url' => $this->sub_image_full_url,
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,
            'since_year' => $this->since_year,
            'story_title' => $this->story_title,
            'story_subtitle' => $this->story_subtitle,
            'story_description' => $this->story_description,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'story_image_1' => $this->story_image_1_full_url,
            'story_image_2' => $this->story_image_2_full_url,
            'stats' => [
                'rooms' => (int) $this->stat_rooms,
                'guests' => $this->stat_guests,
                'years' => $this->stat_years,
                'rating' => $this->stat_rating,
            ],
        ];
    }
}
