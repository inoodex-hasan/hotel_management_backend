<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'hotel_id',
        'room_type_id',
        'check_in',
        'check_out',
        'nights',
        'rooms_count',
        'adult_guests',
        'child_guests',
        'total_price',
        'special_requests',
        'arrival_time',
        'address',
        'city',
        'country',
        'zip_code',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'nights' => 'integer',
            'rooms_count' => 'integer',
            'adult_guests' => 'integer',
            'child_guests' => 'integer',
            'total_price' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
