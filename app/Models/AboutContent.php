<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AboutContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'eyebrow',
        'title',
        'subtitle',
        'description',
        'feature_1_title',
        'feature_1_subtitle',
        'feature_2_title',
        'feature_2_subtitle',
        'main_image_url',
        'sub_image_url',
        'button_text',
        'button_link',
        'since_year',
        'story_title',
        'story_subtitle',
        'story_description',
        'contact_phone',
        'contact_email',
        'story_image_1',
        'story_image_2',
        'stat_rooms',
        'stat_guests',
        'stat_years',
        'stat_rating',
    ];

    protected $casts = [
        'stat_rooms' => 'integer',
    ];

    protected $appends = [
        'main_image_full_url',
        'sub_image_full_url',
        'story_image_1_full_url',
        'story_image_2_full_url',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    private function resolveUrl(?string $url, string $fallback): string
    {
        if (empty($url)) {
            return $fallback;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        if (str_starts_with($url, '/storage/') || str_starts_with($url, 'storage/')) {
            return url(ltrim($url, '/'));
        }

        if (str_starts_with($url, '/images/') || str_starts_with($url, 'images/')) {
            return '/' . ltrim($url, '/');
        }

        return url(ltrim($url, '/'));
    }

    public function getMainImageFullUrlAttribute(): string
    {
        return $this->resolveUrl($this->main_image_url, '/images/room1.avif');
    }

    public function getSubImageFullUrlAttribute(): string
    {
        return $this->resolveUrl($this->sub_image_url, '/images/room2.avif');
    }

    public function getStoryImage1FullUrlAttribute(): string
    {
        return $this->resolveUrl($this->story_image_1, '/images/about/about-hero.jpg');
    }

    public function getStoryImage2FullUrlAttribute(): string
    {
        return $this->resolveUrl($this->story_image_2, '/images/about/about-story.jpg');
    }
}
