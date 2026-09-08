<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $photoUrl = null;
        if (method_exists($this->resource, 'getFirstMediaUrl') && $this->hasMedia('photo')) {
            $photoUrl = $this->getFirstMediaUrl('photo');
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'iata_code' => $this->iata_code,
            'photo_url' => $photoUrl,
            'hotels_count' => $this->whenCounted('hotels'),
            'country' => new CountryResource($this->whenLoaded('country')),
        ];
    }
}
