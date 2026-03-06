<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedeemRewardRequest;
use App\Models\PointTransaction;
use App\Models\RewardItem;
use App\Services\LoyaltyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RewardsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $loyalty = LoyaltyService::getStatus($user);

        $totalPoints = $loyalty['totalPoints'];
        $currentTier = $loyalty['currentTier'];
        $progressPct = $loyalty['progressPct'];
        $ptsToUpgrade = $loyalty['pointsToNextTier'];

        $rewardItems = RewardItem::where('is_active', true)
            ->orderBy('points_cost')
            ->get();

        $activities = PointTransaction::where('user_id', $user->id)
            ->with('booking')
            ->latest()
            ->take(10)
            ->get();

        return view('profile.rewards', compact(
            'totalPoints', 'currentTier', 'progressPct', 'ptsToUpgrade',
            'rewardItems', 'activities'
        ));
    }

    public function redeem(RedeemRewardRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();
        $reward = RewardItem::findOrFail($validated['reward_id']);

        $currentBalance = PointTransaction::where('user_id', $user->id)->sum('points');

        if ($currentBalance < $reward->points_cost) {
            return back()->with('error', 'Not enough points to redeem this reward.');
        }

        PointTransaction::create([
            'user_id' => $user->id,
            'reward_item_id' => $reward->id,
            'type' => 'redeemed',
            'points' => -$reward->points_cost,
            'description' => "Redeemed: {$reward->title}",
        ]);

        return back()->with('success', "Successfully redeemed {$reward->title}!");
    }
}
