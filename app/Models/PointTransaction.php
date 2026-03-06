<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'reward_item_id',
        'type',
        'description',
        'points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function rewardItem()
    {
        return $this->belongsTo(RewardItem::class);
    }
}
