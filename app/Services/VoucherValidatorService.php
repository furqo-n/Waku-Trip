<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Facades\Cache;

class VoucherValidatorService
{
    public function validate(Voucher $voucher, int $userId, ?float $orderAmount = null): array
    {
        if (!$voucher->is_active) {
            return ['valid' => false, 'error' => 'Voucher is not active'];
        }

        if ($voucher->isExpired()) {
            return ['valid' => false, 'error' => 'Voucher has expired'];
        }

        if ($voucher->isNotStarted()) {
            return ['valid' => false, 'error' => 'Voucher is not yet active'];
        }

        if (!$voucher->hasAvailableUsage()) {
            return ['valid' => false, 'error' => 'Voucher usage limit has been reached'];
        }

        if ($voucher->hasUserReachedLimit($userId)) {
            return ['valid' => false, 'error' => 'You have already used this voucher'];
        }

        if (!is_null($orderAmount) && !is_null($voucher->min_order_amount)) {
            if ($orderAmount < $voucher->min_order_amount) {
                return [
                    'valid' => false,
                    'error' => "Minimum order amount of {$voucher->min_order_amount} required",
                ];
            }
        }

        if ($voucher->target_type === 'user_groups' && $voucher->target_user_group) {
            $user = \App\Models\User::find($userId);
            $isMember = $this->isUserInGroup($user, $voucher->target_user_group);

            if (!$isMember) {
                return ['valid' => false, 'error' => 'You are not eligible for this voucher'];
            }
        }

        if (!is_null($voucher->min_user_account_age_days)) {
            $user = \App\Models\User::find($userId);
            $accountAge = \Carbon\Carbon::parse($user->created_at)->diffInDays(now());

            if ($accountAge < $voucher->min_user_account_age_days) {
                return [
                    'valid' => false,
                    'error' => "Account must be at least {$voucher->min_user_account_age_days} days old",
                ];
            }
        }

        return ['valid' => true];
    }

    public function validateApplicability(Voucher $voucher, array $productIds): array
    {
        if ($voucher->target_type === 'all') {
            return ['valid' => true];
        }

        if ($voucher->target_type === 'products') {
            $applicableProducts = $voucher->products()->pluck('packages.id')->toArray();
            $hasMatchingProduct = !empty(array_intersect($productIds, $applicableProducts));

            if (!$hasMatchingProduct) {
                return ['valid' => false, 'error' => 'This voucher does not apply to your selected products'];
            }
        }

        if ($voucher->target_type === 'categories') {
            $applicableCategories = $voucher->categories()->pluck('categories.id')->toArray();
        }

        return ['valid' => true];
    }

    protected function isUserInGroup(\App\Models\User $user, string $groupName): bool
    {
        return \App\Models\VoucherUserGroup::where('name', $groupName)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->exists();
    }
}
