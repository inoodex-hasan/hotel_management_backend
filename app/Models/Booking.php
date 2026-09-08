<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Observers\BookingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(BookingObserver::class)]
class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'reference_no',
        'booking_type',
        'status',
        'total_amount',
        'discount_amount',
        'net_amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_details',
        'coupon_code',
        'notes',
        'booked_at',
    ];

    protected function casts(): array
    {
        return [
            'booked_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'payment_details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotelBooking(): HasOne
    {
        return $this->hasOne(HotelBooking::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class);
    }
}
