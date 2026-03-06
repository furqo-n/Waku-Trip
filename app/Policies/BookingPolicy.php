<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id;
    }

    public function update(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id && $booking->status === 'pending';
    }

    public function pay(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id && $booking->status === 'pending';
    }

    public function manageGuests(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id;
    }
}
