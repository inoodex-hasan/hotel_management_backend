<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Dining extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'dining';

    protected $fillable = [
        'hotel_id',
        'slug',
        'name',
        'label',
        'description',
        'image',
        'location',
        'serves',
        'phone',
        'hours',
        'features',
        'sort_order',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($dining) {
            if (empty($dining->slug)) {
                $dining->slug = Str::slug($dining->name);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
