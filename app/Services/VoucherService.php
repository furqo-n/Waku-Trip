<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Events\VoucherApplied;
use App\Events\VoucherRevoked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class VoucherService
{
    protected VoucherValidatorService $validator;
    protected VoucherCalculatorService $calculator;

    public function __construct(
        VoucherValidatorService $validator,
        VoucherCalculatorService $calculator
    ) {
        $this->validator = $validator;
        $this->calculator = $calculator;
    }

    public function validate(string $code, int $userId, ?float $orderAmount = null): array
    {
        $voucher = $this->getVoucherByCode($code);

        if (!$voucher) {
            return ['valid' => false, 'error' => 'Voucher not found'];
        }

        $validationResult = $this->validator->validate($voucher, $userId, $orderAmount);

        return [
            'valid' => $validationResult['valid'],
            'voucher' => $voucher,
            'error' => $validationResult['error'] ?? null,
            'details' => $validationResult['details'] ?? null,
        ];
    }

    public function apply(string $code, int $userId, float $orderAmount, int $bookingId): array
    {
        return DB::transaction(function () use ($code, $userId, $orderAmount, $bookingId) {
            $voucher = $this->getVoucherByCodeForUpdate($code);

            if (!$voucher) {
                throw new \Exception('Voucher not found');
            }

            $validation = $this->validator->validate($voucher, $userId, $orderAmount);

            if (!$validation['valid']) {
                throw new \Exception($validation['error']);
            }

            $discountAmount = $this->calculator->calculate($voucher, $orderAmount);

            $usage = VoucherUsage::create([
                'voucher_id' => $voucher->id,
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'discount_amount' => $discountAmount,
                'order_amount_before_discount' => $orderAmount,
                'order_amount_after_discount' => $orderAmount - $discountAmount,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'status' => 'applied',
            ]);

            $voucher->increment('used_count');

            $this->clearVoucherCache($voucher->id);

            event(new VoucherApplied($voucher, $usage));

            return [
                'success' => true,
                'usage' => $usage,
                'discount_amount' => $discountAmount,
                'final_amount' => $orderAmount - $discountAmount,
            ];
        });
    }

    public function revoke(int $usageId): bool
    {
        return DB::transaction(function () use ($usageId) {
            $usage = VoucherUsage::findOrFail($usageId);

            if ($usage->status === 'refunded') {
                throw new \Exception('Voucher already refunded');
            }

            $usage->update(['status' => 'refunded']);

            $voucher = $usage->voucher;
            $voucher->decrement('used_count');

            $this->clearVoucherCache($voucher->id);

            event(new VoucherRevoked($voucher, $usage));

            return true;
        });
    }

    public function processPartialRefund(int $usageId, float $refundedAmount): bool
    {
        return DB::transaction(function () use ($usageId, $refundedAmount) {
            $usage = VoucherUsage::findOrFail($usageId);

            $newRefundedAmount = ($usage->refunded_amount ?? 0) + $refundedAmount;

            if ($newRefundedAmount >= $usage->discount_amount) {
                $usage->update([
                    'status' => 'refunded',
                    'refunded_amount' => $newRefundedAmount,
                ]);
                $usage->voucher->decrement('used_count');
            } else {
                $usage->update([
                    'status' => 'partially_refunded',
                    'refunded_amount' => $newRefundedAmount,
                ]);
            }

            $this->clearVoucherCache($usage->voucher_id);

            return true;
        });
    }

    protected function getVoucherByCode(string $code): ?Voucher
    {
        return Cache::remember("voucher:{$code}", 300, function () use ($code) {
            return Voucher::where('code', strtoupper($code))
                ->first();
        });
    }

    protected function getVoucherByCodeForUpdate(string $code): ?Voucher
    {
        return Voucher::where('code', strtoupper($code))
            ->lockForUpdate()
            ->first();
    }

    protected function clearVoucherCache(int $voucherId): void
    {
        $voucher = Voucher::find($voucherId);
        if ($voucher) {
            Cache::forget("voucher:{$voucher->code}");
        }
    }

    public function getAvailableVouchersForUser(int $userId): array
    {
        $vouchers = Voucher::active()
            ->hasAvailableUsage()
            ->whereDoesntHave('usages', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereNotIn('status', ['refunded']);
            })
            ->with(['products', 'categories'])
            ->get();

        return $vouchers->map(function ($voucher) {
            return [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'title' => $voucher->title,
                'description' => $voucher->description,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'max_discount' => $voucher->max_discount,
                'min_order_amount' => $voucher->min_order_amount,
                'expires_at' => $voucher->expires_at,
            ];
        })->toArray();
    }
}
