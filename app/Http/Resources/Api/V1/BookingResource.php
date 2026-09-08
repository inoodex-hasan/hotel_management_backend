<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'reference_no' => $this->reference_no,
            'booking_id' => $this->reference_no,
            'booking_type' => $this->booking_type,
            'status' => $this->status,
            'total_amount' => (float) $this->total_amount,
            'discount_amount' => (float) $this->discount_amount,
            'net_amount' => (float) $this->net_amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'payment_details' => $this->payment_details,
            'coupon_code' => $this->coupon_code,
            'notes' => $this->notes,
            'booked_at' => $this->booked_at?->toIso8601String(),
            'hotel_booking' => new HotelBookingResource($this->whenLoaded('hotelBooking')),
            'passengers' => BookingPassengerResource::collection($this->whenLoaded('passengers')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
