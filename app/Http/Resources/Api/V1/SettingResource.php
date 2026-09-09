<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'app_name' => $this['app_name'] ?? 'The Azura Hotel & Suites',
            'hotel_tagline' => $this['hotel_tagline'] ?? '',
            'app_logo' => $this['app_logo'] ?? null,
            'app_favicon' => $this['app_favicon'] ?? null,
            'contact_email' => $this['contact_email'] ?? 'reservation.theazura@gmail.com',
            'contact_phone' => $this['contact_phone'] ?? '+880 1401 777 888',
            'address' => $this['address'] ?? 'Marine Drive Road, Cox\'s Bazar, Bangladesh',
            'facebook_url' => $this['facebook_url'] ?? '',
            'instagram_url' => $this['instagram_url'] ?? '',
            'twitter_url' => $this['twitter_url'] ?? '',
            'youtube_url' => $this['youtube_url'] ?? '',

            // Experience Banner
            'experience_label' => $this['experience_label'] ?? 'The Azura Experience',
            'experience_title' => $this['experience_title'] ?? 'Where every stay becomes a memory.',
            'experience_subtitle' => $this['experience_subtitle'] ?? '',
            'experience_image' => $this['experience_image'] ?? '/images/room2.avif',
            'experience_video_url' => $this['experience_video_url'] ?? null,
            'experience_button_text' => $this['experience_button_text'] ?? 'Explore Suites',
            'experience_button_link' => $this['experience_button_link'] ?? '/rooms',
        ];
    }
}
