<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelBookingResource extends JsonResource
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
            'check_in' => $this->check_in?->toDateString(),
            'check_out' => $this->check_out?->toDateString(),
            'nights' => (int) $this->nights,
            'rooms_count' => (int) $this->rooms_count,
            'adult_guests' => (int) $this->adult_guests,
            'child_guests' => (int) $this->child_guests,
            'total_price' => (float) $this->total_price,
            'special_requests' => $this->special_requests,
            'arrival_time' => $this->arrival_time,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'zip_code' => $this->zip_code,
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
        ];
    }
}
