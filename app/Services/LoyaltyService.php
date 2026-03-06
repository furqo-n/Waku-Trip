<?php

namespace App\Services;

use App\Models\PointTransaction;
use App\Models\User;

class LoyaltyService
{
    /**
     * Get tier definitions.
     */
    public static function getTiers()
    {
        return [
            ['name' => 'Bronze Traveler',  'min' => 0,    'class' => 'tier-bronze', 'next' => 'Silver Traveler', 'nextMin' => 1000],
            ['name' => 'Silver Traveler',  'min' => 1000, 'class' => 'tier-silver', 'next' => 'Gold Traveler',   'nextMin' => 2500],
            ['name' => 'Gold Traveler',    'min' => 2500, 'class' => 'tier-gold',   'next' => 'Master Traveler', 'nextMin' => 4000],
            ['name' => 'Master Traveler',  'min' => 4000, 'class' => 'tier-master', 'next' => null,              'nextMin' => null],
        ];
    }

    /**
     * Calculate current loyalty status for a user.
     */
    public static function getStatus(User $user)
    {
        $totalPoints = (int) max(0, PointTransaction::where('user_id', $user->id)->sum('points'));
        
        $tiers = self::getTiers();
        $currentTier = $tiers[0];

        foreach ($tiers as $tier) {
            if ($totalPoints >= $tier['min']) {
                $currentTier = $tier;
            }
        }

        $progressPct = 0;
        $pointsToNextTier = 0;
        
        if ($currentTier['nextMin']) {
            $range = $currentTier['nextMin'] - $currentTier['min'];
            $earned = $totalPoints - $currentTier['min'];
            $progressPct = min(100, round(($earned / $range) * 100));
            $pointsToNextTier = $currentTier['nextMin'] - $totalPoints;
        } else {
            $progressPct = 100;
        }

        return [
            'totalPoints' => $totalPoints,
            'currentTier' => $currentTier,
            'progressPct' => $progressPct,
            'pointsToNextTier' => $pointsToNextTier,
        ];
    }
}
