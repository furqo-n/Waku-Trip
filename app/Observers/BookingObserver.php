<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\PointTransaction;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Check if status changed to 'completed'
        if ($booking->isDirty('status') && $booking->status === 'completed') {
            
            // Calculate points: 1 point per $1 spent (rounded down)
            $pointsEarned = (int) floor($booking->total_price);

            // Prevent duplicate transactions 
            $exists = PointTransaction::where('booking_id', $booking->id)
                ->where('type', 'earned')
                ->exists();

            if (!$exists && $pointsEarned > 0) {
                
                // Get trip title safely
                $tripTitle = optional(optional($booking->tripSchedule)->package)->title;
                $desc = $tripTitle ? "Completed Trip: $tripTitle" : "Booking #{$booking->booking_code}";

                PointTransaction::create([
                    'user_id'     => $booking->user_id,
                    'booking_id'  => $booking->id,
                    'type'        => 'earned',
                    'points'      => $pointsEarned,
                    'description' => $desc,
                ]);
            }
        }
    }
}
