<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPassenger extends Model
{
    protected $fillable = [
        'booking_id',
        'saved_traveler_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'type',
        'gender',
        'date_of_birth',
        'passport_number',
        'passport_expiry',
        'nationality',
        'seat_preference',
        'seat_number',
        'meal_preference',
        'is_lead_passenger',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
            'is_lead_passenger' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function savedTraveler(): BelongsTo
    {
        return $this->belongsTo(SavedTraveler::class);
    }
}