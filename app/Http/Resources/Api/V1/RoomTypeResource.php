<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thumbnail = $this->image;
        if (empty($thumbnail) && method_exists($this->resource, 'getFirstMediaUrl')) {
            $thumbnail = $this->resource->getFirstMediaUrl('thumbnail');
        }

        $gallery = [];
        if (!empty($this->gallery)) {
            $gallery = is_array($this->gallery) ? $this->gallery : (json_decode($this->gallery, true) ?: []);
        }
        if (empty($gallery) && method_exists($this->resource, 'getMedia')) {
            $galleryMedia = $this->resource->getMedia('gallery');
            foreach ($galleryMedia as $media) {
                $gallery[] = $media->getFullUrl();
            }
        }

        if (empty($thumbnail) && !empty($gallery[0])) {
            $thumbnail = $gallery[0];
        }

        // Format thumbnail URL if it is a storage path
        if ($thumbnail && (str_starts_with($thumbnail, '/storage/') || str_starts_with($thumbnail, 'storage/'))) {
            $thumbnail = url(ltrim($thumbnail, '/'));
        }

        // Format gallery URLs if they are storage paths
        $formattedGallery = array_map(function ($img) {
            if ($img && (str_starts_with($img, '/storage/') || str_starts_with($img, 'storage/'))) {
                return url(ltrim($img, '/'));
            }
            return $img;
        }, $gallery);

        $amenities = $this->amenities ?: [];
        if (is_string($amenities)) {
            $amenities = json_decode($amenities, true) ?: [];
        }

        $highlights = $this->highlights ?: [];
        if (is_string($highlights)) {
            $highlights = json_decode($highlights, true) ?: [];
        }

        $priceFormatted = number_format((float) $this->base_price_per_night, 0);

        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'slug' => $this->slug,
            'name' => $this->name,
            'subtitle' => $this->subtitle ?? '',
            'tag' => $this->tag ?? '',
            'description' => $this->description,
            'long_description' => $this->long_description ?: $this->description,
            'longDescription' => $this->long_description ?: $this->description,
            'image' => $thumbnail,
            'gallery' => $formattedGallery,
            'thumbnail_url' => $thumbnail,
            'gallery_urls' => $formattedGallery,
            'price' => $priceFormatted,
            'base_price_per_night' => (float) $this->base_price_per_night,
            'floor' => $this->floor ?? '',
            'bed' => $this->bed_type ?? '1 King Bed',
            'bed_type' => $this->bed_type ?? '1 King Bed',
            'maxGuests' => ($this->capacity_adults ?? 2) . ' Adults',
            'capacity_adults' => (int) ($this->capacity_adults ?? 2),
            'capacity_children' => (int) ($this->capacity_children ?? 0),
            'size' => $this->room_size ?? '350 sq ft',
            'room_size' => $this->room_size ?? '350 sq ft',
            'stars' => (int) ($this->stars ?? 4),
            'amenities' => $amenities,
            'highlights' => $highlights,
            'total_rooms' => (int) ($this->total_rooms ?? 10),
            'available_rooms' => (int) ($this->available_rooms ?? 10),
            'is_refundable' => (bool) ($this->is_refundable ?? true),
            'cancellation_policy' => $this->cancellation_policy,
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
        ];
    }
}
