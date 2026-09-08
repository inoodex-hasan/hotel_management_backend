<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Hotel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'city_id',
        'name',
        'slug',
        'description',
        'star_rating',
        'address',
        'latitude',
        'longitude',
        'contact_phone',
        'contact_email',
        'check_in_time',
        'check_out_time',
        'is_international',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected static function booted()
    {
        static::saving(function ($hotel) {
            if (empty($hotel->slug) && !empty($hotel->name)) {
                $hotel->slug = static::generateUniqueSlug($hotel->name);
            }
        });
    }

    protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function getRouteKey(): mixed
    {
        return $this->slug ?: (string) $this->getKey();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'slug', $value)
            ->orWhere('id', $value)
            ->first();
    }

    protected function casts(): array
    {
        return [
            'star_rating' => 'integer',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_international' => 'boolean',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(HotelAmenity::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->acceptsFile(fn ($file) => in_array($file->mimeType, ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/gif']));
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    public function hotelBookings(): HasMany
    {
        return $this->hasMany(HotelBooking::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(HotelPolicy::class);
    }
}
