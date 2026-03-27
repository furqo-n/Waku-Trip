<?php

namespace App\Events;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoucherApplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Voucher $voucher,
        public VoucherUsage $usage
    ) {}
}
