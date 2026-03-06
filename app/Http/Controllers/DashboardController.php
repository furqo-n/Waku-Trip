<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\LoyaltyService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $upcomingBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'pending', 'paid'])
            ->whereHas('tripSchedule', function ($q) {
                $q->where('start_date', '>=', now());
            })
            ->with(['tripSchedule.package.media'])
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $nextTrip = $upcomingBookings->first();

        $pastBookings = Booking::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('status', 'completed')
                  ->orWhereHas('tripSchedule', function ($sq) {
                      $sq->where('end_date', '<', now());
                  });
            })
            ->with(['tripSchedule.package.media'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalBookings = Booking::where('user_id', $user->id)->count();

        $loyalty = LoyaltyService::getStatus($user);

        $rewardPoints = $loyalty['totalPoints'];
        $tier = $loyalty['currentTier']['name'];
        $nextTier = $loyalty['currentTier']['next'] ?? 'Max Level';
        $pointsToNextTier = $loyalty['pointsToNextTier'];
        $tierProgress = $loyalty['progressPct'];

        return view('profile.dashboard', compact(
            'user', 'upcomingBookings', 'nextTrip', 'pastBookings',
            'rewardPoints', 'tier', 'nextTier', 'pointsToNextTier', 'tierProgress', 'totalBookings'
        ));
    }
}
