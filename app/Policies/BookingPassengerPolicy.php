<?php

namespace App\Policies;

use App\Models\BookingPassenger;
use App\Models\User;

class BookingPassengerPolicy
{
    public function delete(User $user, BookingPassenger $guest): bool
    {
        return $guest->booking && $guest->booking->user_id === $user->id;
    }
}
