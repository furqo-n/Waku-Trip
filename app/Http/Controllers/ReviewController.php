<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();

        $booking = Booking::with('tripSchedule.package')
            ->where('id', $validated['booking_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $package = $booking->tripSchedule->package;

        $package->reviews()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
