<?php

namespace App\Services;

use App\Models\Voucher;

class VoucherCalculatorService
{
    public function calculate(Voucher $voucher, float $orderAmount): float
    {
        return match ($voucher->type) {
            'percentage' => $this->calculatePercentage($voucher, $orderAmount),
            'fixed_amount' => $this->calculateFixedAmount($voucher, $orderAmount),
            default => 0,
        };
    }

    protected function calculatePercentage(Voucher $voucher, float $orderAmount): float
    {
        $discount = ($orderAmount * $voucher->value) / 100;

        if (!is_null($voucher->max_discount)) {
            $discount = min($discount, $voucher->max_discount);
        }

        return round($discount, 2);
    }

    protected function calculateFixedAmount(Voucher $voucher, float $orderAmount): float
    {
        return min($voucher->value, $orderAmount);
    }

    public function calculateMultiple(array $vouchers, float $orderAmount): array
    {
        $totalDiscount = 0;
        $appliedVouchers = [];

        foreach ($vouchers as $voucher) {
            $discount = $this->calculate($voucher, $orderAmount - $totalDiscount);
            $totalDiscount += $discount;
            $appliedVouchers[] = [
                'voucher_id' => $voucher->id,
                'discount' => $discount,
            ];
        }

        return [
            'total_discount' => $totalDiscount,
            'final_amount' => $orderAmount - $totalDiscount,
            'applied_vouchers' => $appliedVouchers,
        ];
    }
}
