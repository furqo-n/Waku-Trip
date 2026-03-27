<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherUsage extends Model
{
    protected $fillable = [
        'voucher_id',
        'user_id',
        'booking_id',
        'discount_amount',
        'order_amount_before_discount',
        'order_amount_after_discount',
        'ip_address',
        'user_agent',
        'status',
        'refunded_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'order_amount_before_discount' => 'decimal:2',
        'order_amount_after_discount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isFullyRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isPartiallyRefunded(): bool
    {
        return $this->status === 'partially_refunded';
    }
}
