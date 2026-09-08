<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class RoomType extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'hotel_id',
        'slug',
        'name',
        'subtitle',
        'tag',
        'description',
        'long_description',
        'image',
        'gallery',
        'floor',
        'bed_type',
        'room_size',
        'stars',
        'amenities',
        'highlights',
        'capacity_adults',
        'capacity_children',
        'total_rooms',
        'available_rooms',
        'base_price_per_night',
        'is_refundable',
        'cancellation_policy',
    ];

    protected static function booted()
    {
        static::creating(function ($roomType) {
            if (empty($roomType->slug)) {
                $roomType->slug = Str::slug($roomType->name);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'capacity_adults' => 'integer',
            'capacity_children' => 'integer',
            'total_rooms' => 'integer',
            'available_rooms' => 'integer',
            'stars' => 'integer',
            'base_price_per_night' => 'decimal:2',
            'is_refundable' => 'boolean',
            'highlights' => 'array',
            'amenities' => 'array',
            'gallery' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function hotelBookings(): HasMany
    {
        return $this->hasMany(HotelBooking::class);
    }
}
