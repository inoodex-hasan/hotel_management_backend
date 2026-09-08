<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'title',
        'subtitle',
        'description',
        'badge_text',
        'button_text',
        'button_link',
        'image_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_full_url'];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order', 'asc');
    }

    public function getImageFullUrlAttribute(): string
    {
        if (empty($this->image_url)) {
            return asset('assets/images/placeholder.webp');
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }

        if (str_starts_with($this->image_url, '/storage/') || str_starts_with($this->image_url, 'storage/')) {
            return url(ltrim($this->image_url, '/'));
        }

        if (str_starts_with($this->image_url, '/images/') || str_starts_with($this->image_url, 'images/')) {
            return '/' . ltrim($this->image_url, '/');
        }

        return url(ltrim($this->image_url, '/'));
    }
}
