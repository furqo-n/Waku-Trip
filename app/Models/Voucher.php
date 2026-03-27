<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'description',
        'type',
        'value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'is_stackable',
        'max_stackable',
        'target_type',
        'target_user_group',
        'min_user_account_age_days',
        'created_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_stackable' => 'boolean',
        'max_stackable' => 'integer',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'voucher_products', 'voucher_id', 'product_id')
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'voucher_categories')
            ->withTimestamps();
    }

    public function usages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function userUsages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>=', now());
    }

    public function scopeHasAvailableUsage($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('usage_limit')
              ->orWhereRaw('used_count < usage_limit');
        });
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function code(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value),
            set: fn ($value) => strtoupper($value),
        );
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isNotStarted(): bool
    {
        return $this->starts_at->isFuture();
    }

    public function hasAvailableUsage(): bool
    {
        return is_null($this->usage_limit) || $this->used_count < $this->usage_limit;
    }

    public function hasUserReachedLimit(int $userId): bool
    {
        if (is_null($this->usage_limit_per_user)) {
            return false;
        }

        $userUsageCount = $this->usages()
            ->where('user_id', $userId)
            ->whereIn('status', ['applied', 'used'])
            ->count();

        return $userUsageCount >= $this->usage_limit_per_user;
    }

    public function isApplicableToProduct(Package $product): bool
    {
        if ($this->target_type === 'all') {
            return true;
        }

        if ($this->target_type === 'products') {
            return $this->products()->where('packages.id', $product->id)->exists();
        }

        if ($this->target_type === 'categories') {
            return $product->categories()
                ->whereIn('categories.id', $this->categories->pluck('id'))
                ->exists();
        }

        return false;
    }
}
