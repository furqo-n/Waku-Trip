<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use App\Events\VoucherExpired;
use Illuminate\Console\Command;

class ExpireVouchers extends Command
{
    protected $signature = 'vouchers:expire {--dry-run : Show what would be expired without making changes}';
    protected $description = 'Mark expired vouchers as inactive and dispatch events';

    public function handle(): int
    {
        $expiredVouchers = Voucher::where('expires_at', '<', now())
            ->where('is_active', true)
            ->get();

        if ($expiredVouchers->isEmpty()) {
            $this->info('No vouchers to expire.');
            return Command::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("Found {$expiredVouchers->count()} vouchers that would be expired:");
            $expiredVouchers->each(fn ($v) => $this->line("- {$v->code} ({$v->title})"));
            return Command::SUCCESS;
        }

        $count = $expiredVouchers->count();

        $expiredVouchers->each(function ($voucher) {
            $voucher->update(['is_active' => false]);
            event(new VoucherExpired($voucher));
            $this->info("Expired voucher: {$voucher->code}");
        });

        $this->info("Successfully expired {$count} vouchers.");

        return Command::SUCCESS;
    }
}
