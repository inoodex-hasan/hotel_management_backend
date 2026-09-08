<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Str;

class BookingObserver
{
    /**
     * Handle the Booking "creating" event.
     */
    public function creating(Booking $booking): void
    {
        if (empty($booking->reference_no)) {
            $booking->reference_no = $this->generateUniqueReference();
        }
    }

    /**
     * Generate a unique reference number.
     */
    protected function generateUniqueReference(): string
    {
        $prefix = 'BK-';
        $year = date('Y');
        
        // Find the last reference for this year
        $lastBooking = Booking::where('reference_no', 'like', $prefix . $year . '%')
            ->orderBy('reference_no', 'desc')
            ->first();

        if ($lastBooking) {
            $lastSequence = (int) substr($lastBooking->reference_no, -4);
            $newSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '0001';
        }

        $reference = $prefix . $year . $newSequence;

        // Final check for uniqueness (just in case of race conditions)
        while (Booking::where('reference_no', $reference)->exists()) {
            $newSequence = str_pad((int)$newSequence + 1, 4, '0', STR_PAD_LEFT);
            $reference = $prefix . $year . $newSequence;
        }

        return $reference;
    }
}
