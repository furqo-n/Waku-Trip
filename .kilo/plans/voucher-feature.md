# Voucher/Coupon Feature Development Plan

## Project Overview
- **Laravel Version**: 12.0
- **PHP Version**: 8.2+
- **Admin Panel**: Filament 5.0
- **Frontend**: Blade/Livewire (based on existing stack)
- **Database**: MySQL/SQLite

---

## 1. Database Design

### 1.1 Migration Files

#### Main Vouchers Table
```php
// database/migrations/XXXX_XX_XX_create_vouchers_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping', 'buy_x_get_y']);
            $table->decimal('value', 10, 2); // percentage (0-100) or fixed amount
            $table->decimal('max_discount', 10, 2)->nullable(); // cap for percentage vouchers
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable(); // global usage limit
            $table->unsignedInteger('usage_limit_per_user')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_stackable')->default(false); // can combine with other vouchers
            $table->unsignedInteger('max_stackable')->default(0); // max vouchers that can be combined
            $table->enum('target_type', ['all', 'products', 'categories', 'user_groups'])->default('all');
            $table->string('target_user_group')->nullable(); // e.g., 'new_users', 'vip'
            $table->unsignedInteger('min_user_account_age_days')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('code');
            $table->index(['is_active', 'starts_at', 'expires_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
```

#### Voucher Usages Table (Track every redemption)
```php
// database/migrations/XXXX_XX_XX_create_voucher_usages_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('order_amount_before_discount', 10, 2);
            $table->decimal('order_amount_after_discount', 10, 2);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('status', ['applied', 'used', 'refunded', 'partially_refunded'])->default('used');
            $table->decimal('refunded_amount', 10, 2)->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['voucher_id', 'user_id']);
            $table->index(['user_id', 'created_at']);
            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
    }
};
```

#### Voucher Products (Many-to-Many for specific products)
```php
// database/migrations/XXXX_XX_XX_create_voucher_products_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('packages')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['voucher_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_products');
    }
};
```

#### Voucher Categories (Many-to-Many for specific categories)
```php
// database/migrations/XXXX_XX_XX_create_voucher_categories_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['voucher_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_categories');
    }
};
```

#### Voucher User Groups (For targeted vouchers)
```php
// database/migrations/XXXX_XX_XX_create_voucher_user_groups_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 'new_users', 'vip', 'wholesale'
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('voucher_user_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_user_group_id')->constrained('voucher_user_groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('member_since')->now();
            $table->timestamps();

            $table->unique(['voucher_user_group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_user_group_members');
        Schema::dropIfExists('voucher_user_groups');
    }
};
```

---

## 2. Eloquent Models

### 2.1 Voucher Model
```php
// app/Models/Voucher.php
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

    // Relationships
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'voucher_products')
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

    // Scopes
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

    // Accessors & Mutators
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
            return $product->relatedCategories()
                ->whereIn('categories.id', $this->categories->pluck('id'))
                ->exists();
        }

        return false;
    }
}
```

### 2.2 VoucherUsage Model
```php
// app/Models/VoucherUsage.php
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
```

### 2.3 VoucherUserGroup Model
```php
// app/Models/VoucherUserGroup.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VoucherUserGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'voucher_user_group_members')
            ->withTimestamps();
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'target_user_group', 'name');
    }
}
```

---

## 3. Service Layer Architecture

### 3.1 VoucherService (Main Service)
```php
// app/Services/VoucherService.php
<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\Booking;
use App\Events\VoucherApplied;
use App\Events\VoucherRevoked;
use App\Events\VoucherExpired;
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

            // Validate again within transaction
            $validation = $this->validator->validate($voucher, $userId, $orderAmount);

            if (!$validation['valid']) {
                throw new \Exception($validation['error']);
            }

            // Calculate discount
            $discountAmount = $this->calculator->calculate($voucher, $orderAmount);

            // Create usage record
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

            // Update voucher usage count
            $voucher->increment('used_count');

            // Clear cache
            $this->clearVoucherCache($voucher->id);

            // Dispatch event
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

            $voucher = $voucher = $usage->voucher;
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
                    ->whereNotIn('status', ['refunded'])
                    ->whereRaw('used_count >= usage_limit_per_user');
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
```

### 3.2 VoucherValidatorService
```php
// app/Services/VoucherValidatorService.php
<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\User;
use Carbon\Carbon;

class VoucherValidatorService
{
    public function validate(Voucher $voucher, int $userId, ?float $orderAmount = null): array
    {
        $errors = [];

        // Check if voucher is active
        if (!$voucher->is_active) {
            return ['valid' => false, 'error' => 'Voucher is not active'];
        }

        // Check expiry
        if ($voucher->isExpired()) {
            return ['valid' => false, 'error' => 'Voucher has expired'];
        }

        // Check if voucher has started
        if ($voucher->isNotStarted()) {
            return ['valid' => false, 'error' => 'Voucher is not yet active'];
        }

        // Check usage limit
        if (!$voucher->hasAvailableUsage()) {
            return ['valid' => false, 'error' => 'Voucher usage limit has been reached'];
        }

        // Check per-user limit
        if ($voucher->hasUserReachedLimit($userId)) {
            return ['valid' => false, 'error' => 'You have already used this voucher'];
        }

        // Check minimum order amount
        if (!is_null($orderAmount) && !is_null($voucher->min_order_amount)) {
            if ($orderAmount < $voucher->min_order_amount) {
                return [
                    'valid' => false,
                    'error' => "Minimum order amount of {$voucher->min_order_amount} required",
                ];
            }
        }

        // Check user group membership
        if ($voucher->target_type === 'user_groups' && $voucher->target_user_group) {
            $user = User::find($userId);
            $isMember = $this->isUserInGroup($user, $voucher->target_user_group);

            if (!$isMember) {
                return ['valid' => false, 'error' => 'You are not eligible for this voucher'];
            }
        }

        // Check minimum account age
        if (!is_null($voucher->min_user_account_age_days)) {
            $user = User::find($userId);
            $accountAge = Carbon::parse($user->created_at)->diffInDays(now());

            if ($accountAge < $voucher->min_user_account_age_days) {
                return [
                    'valid' => false,
                    'error' => "Account must be at least {$voucher->min_user_account_age_days} days old",
                ];
            }
        }

        return ['valid' => true];
    }

    public function validateApplicability(Voucher $voucher, float $orderAmount, array $productIds): array
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
            // Would need to check products in cart against categories
            // This requires product-to-category relationship check
        }

        return ['valid' => true];
    }

    protected function isUserInGroup(User $user, string $groupName): bool
    {
        return \App\Models\VoucherUserGroup::where('name', $groupName)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->exists();
    }
}
```

### 3.3 VoucherCalculatorService
```php
// app/Services/VoucherCalculatorService.php
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
            'free_shipping' => 0, // Handle separately in shipping calculation
            'buy_x_get_y' => $this->calculateBuyXGetY($voucher, $orderAmount),
            default => 0,
        };
    }

    protected function calculatePercentage(Voucher $voucher, float $orderAmount): float
    {
        $discount = ($orderAmount * $voucher->value) / 100;

        // Apply max discount cap if set
        if (!is_null($voucher->max_discount)) {
            $discount = min($discount, $voucher->max_discount);
        }

        return round($discount, 2);
    }

    protected function calculateFixedAmount(Voucher $voucher, float $orderAmount): float
    {
        // Fixed amount cannot exceed order amount
        return min($voucher->value, $orderAmount);
    }

    protected function calculateBuyXGetY(Voucher $voucher, float $orderAmount): float
    {
        // Complex logic for buy X get Y
        // Would need additional fields: buy_quantity, get_quantity, get_product_id
        return 0;
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
```

---

## 4. Form Request Validation

### 4.1 StoreVoucherRequest
```php
// app/Http/Requests/StoreVoucherRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handle authorization in controller
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:vouchers,code',
                'regex:/^[A-Z0-9_-]+$/',
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', Rule::in(['percentage', 'fixed_amount', 'free_shipping', 'buy_x_get_y'])],
            'value' => ['required', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['required', 'date', 'after_or_equal:today'],
            'expires_at' => ['required', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
            'is_stackable' => ['boolean'],
            'max_stackable' => ['nullable', 'integer', 'min:0'],
            'target_type' => ['required', Rule::in(['all', 'products', 'categories', 'user_groups'])],
            'target_user_group' => ['nullable', 'string', 'max:100'],
            'min_user_account_age_days' => ['nullable', 'integer', 'min:0'],
            'product_ids' => ['array', 'nullable'],
            'product_ids.*' => ['integer', 'exists:packages,id'],
            'category_ids' => ['array', 'nullable'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Voucher code can only contain uppercase letters, numbers, underscores, and hyphens.',
            'expires_at.after' => 'Expiry date must be after start date.',
        ];
    }
}
```

### 4.2 ApplyVoucherRequest
```php
// app/Http/Requests/ApplyVoucherRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'order_amount' => ['required', 'numeric', 'min:0'],
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'product_ids' => ['array', 'nullable'],
            'product_ids.*' => ['integer'],
        ];
    }
}
```

---

## 5. API Controllers & Resources

### 5.1 Admin Voucher Controller (API)
```php
// app/Http/Controllers/Api/Admin/VoucherController.php
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Http\Resources\VoucherCollection;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index(Request $request): VoucherCollection
    {
        $query = Voucher::with(['products', 'categories']);

        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        if ($request->has('status')) {
            match ($request->status) {
                'active' => $query->active(),
                'expired' => $query->where('expires_at', '<', now()),
                'inactive' => $query->where('is_active', false),
                default => null,
            };
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                  ->orWhere('title', 'like', "%{$request->search}%");
            });
        }

        $vouchers = $query->orderByDesc('created_at')->paginate(20);

        return new VoucherCollection($vouchers);
    }

    public function store(StoreVoucherRequest $request): JsonResponse
    {
        $voucher = Voucher::create($request->validated());

        // Attach products if type is products
        if ($request->has('product_ids')) {
            $voucher->products()->sync($request->product_ids);
        }

        // Attach categories if type is categories
        if ($request->has('category_ids')) {
            $voucher->categories()->sync($request->category_ids);
        }

        return response()->json([
            'message' => 'Voucher created successfully',
            'voucher' => new VoucherResource($voucher->load(['products', 'categories'])),
        ], 201);
    }

    public function show(Voucher $voucher): JsonResponse
    {
        return response()->json([
            'voucher' => new VoucherResource($voucher->load(['products', 'categories', 'usages'])),
        ]);
    }

    public function update(UpdateVoucherRequest $request, Voucher $voucher): JsonResponse
    {
        $voucher->update($request->validated());

        if ($request->has('product_ids')) {
            $voucher->products()->sync($request->product_ids);
        }

        if ($request->has('category_ids')) {
            $voucher->categories()->sync($request->category_ids);
        }

        return response()->json([
            'message' => 'Voucher updated successfully',
            'voucher' => new VoucherResource($voucher->fresh(['products', 'categories'])),
        ]);
    }

    public function destroy(Voucher $voucher): JsonResponse
    {
        $voucher->delete();

        return response()->json(['message' => 'Voucher deleted successfully']);
    }

    public function duplicate(Voucher $voucher): JsonResponse
    {
        $newVoucher = $voucher->replicate();
        $newVoucher->code = $this->generateUniqueCode();
        $newVoucher->title = $voucher->title . ' (Copy)';
        $newVoucher->used_count = 0;
        $newVoucher->save();

        // Copy relationships
        $newVoucher->products()->sync($voucher->products->pluck('id'));
        $newVoucher->categories()->sync($voucher->categories->pluck('id'));

        return response()->json([
            'message' => 'Voucher duplicated successfully',
            'voucher' => new VoucherResource($newVoucher->load(['products', 'categories'])),
        ]);
    }

    public function toggleStatus(Voucher $voucher): JsonResponse
    {
        $voucher->update(['is_active' => !$voucher->is_active]);

        return response()->json([
            'message' => 'Voucher status updated',
            'is_active' => $voucher->is_active,
        ]);
    }

    public function bulkGenerate(Request $request): JsonResponse
    {
        $request->validate([
            'prefix' => ['required', 'string', 'max:10'],
            'count' => ['required', 'integer', 'min:1', 'max:1000'],
            'type' => ['required', Rule::in(['percentage', 'fixed_amount', 'free_shipping'])],
            'value' => ['required', 'numeric', 'min:0'],
            'expires_at' => ['required', 'date', 'after:today'],
        ]);

        $vouchers = [];
        $prefix = strtoupper($request->prefix);

        for ($i = 0; $i < $request->count; $i++) {
            $vouchers[] = [
                'code' => $prefix . strtoupper(Str::random(8)),
                'title' => "Bulk Voucher {$i + 1}",
                'type' => $request->type,
                'value' => $request->value,
                'expires_at' => $request->expires_at,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Voucher::insert($vouchers);

        return response()->json([
            'message' => "Generated {$request->count} vouchers successfully",
            'count' => $request->count,
        ]);
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $vouchers = Voucher::with(['products', 'categories'])->get();

        return response()->streamDownload(function () use ($vouchers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Code', 'Title', 'Type', 'Value', 'Usage Limit', 'Used', 'Expires At', 'Status']);

            foreach ($vouchers as $voucher) {
                fputcsv($handle, [
                    $voucher->code,
                    $voucher->title,
                    $voucher->type,
                    $voucher->value,
                    $voucher->usage_limit ?? 'Unlimited',
                    $voucher->used_count,
                    $voucher->expires_at->format('Y-m-d'),
                    $voucher->is_active ? 'Active' : 'Inactive',
                ]);
            }

            fclose($handle);
        }, 'vouchers.csv', ['Content-Type' => 'text/csv']);
    }

    protected function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (Voucher::where('code', $code)->exists());

        return $code;
    }
}
```

### 5.2 Customer Voucher Controller (API)
```php
// app/Http/Controllers/Api/Customer/VoucherController.php
<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoucherResource;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    protected VoucherService $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'order_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $result = $this->voucherService->validate(
            $request->code,
            auth()->id(),
            $request->order_amount
        );

        if ($result['valid']) {
            return response()->json([
                'valid' => true,
                'voucher' => new VoucherResource($result['voucher']),
                'message' => 'Voucher is valid',
            ]);
        }

        return response()->json([
            'valid' => false,
            'error' => $result['error'],
        ], 422);
    }

    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'order_amount' => ['required', 'numeric', 'min:0'],
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
        ]);

        try {
            $result = $this->voucherService->apply(
                $request->code,
                auth()->id(),
                $request->order_amount,
                $request->booking_id
            );

            return response()->json([
                'success' => true,
                'discount_amount' => $result['discount_amount'],
                'final_amount' => $result['final_amount'],
                'message' => 'Voucher applied successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function available(): JsonResponse
    {
        $vouchers = $this->voucherService->getAvailableVouchersForUser(auth()->id());

        return response()->json([
            'vouchers' => VoucherResource::collection(collect($vouchers)),
        ]);
    }

    public function myUsages(): JsonResponse
    {
        $usages = \App\Models\VoucherUsage::with('voucher')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($usages);
    }
}
```

### 5.3 API Resources
```php
// app/Http/Resources/VoucherResource.php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'value' => (float) $this->value,
            'max_discount' => $this->max_discount ? (float) $this->max_discount : null,
            'min_order_amount' => $this->min_order_amount ? (float) $this->min_order_amount : null,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'usage_remaining' => $this->usage_limit ? $this->usage_limit - $this->used_count : null,
            'usage_limit_per_user' => $this->usage_limit_per_user,
            'starts_at' => $this->starts_at->toIso8601String(),
            'expires_at' => $this->expires_at->toIso8601String(),
            'is_active' => $this->is_active,
            'is_expired' => $this->isExpired(),
            'is_stackable' => $this->is_stackable,
            'target_type' => $this->target_type,
            'products' => $this->when($this->relationLoaded('products'), function () {
                return $this->products->map(fn ($p) => ['id' => $p->id, 'title' => $p->title]);
            }),
            'categories' => $this->when($this->relationLoaded('categories'), function () {
                return $this->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name]);
            }),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
```

---

## 6. Events & Listeners

### 6.1 Events
```php
// app/Events/VoucherApplied.php
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

// app/Events/VoucherRevoked.php
class VoucherRevoked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Voucher $voucher,
        public VoucherUsage $usage
    ) {}
}

// app/Events/VoucherExpired.php
class VoucherExpired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Voucher $voucher) {}
}
```

### 6.2 Event Service Provider Registration
```php
// app/Providers/EventServiceProvider.php
<?php

namespace App\Providers;

use App\Events\VoucherApplied;
use App\Listeners\LogVoucherUsage;
use App\Listeners\SendVoucherAppliedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VoucherApplied::class => [
            LogVoucherUsage::class,
            SendVoucherAppliedNotification::class,
        ],
        VoucherRevoked::class => [
            LogVoucherRevoked::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
```

---

## 7. Artisan Commands

### 7.1 Expire Vouchers Command
```php
// app/Console/Commands/ExpireVouchers.php
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
```

### 7.2 Generate Bulk Vouchers Command
```php
// app/Console/Commands/GenerateBulkVouchers.php
<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateBulkVouchers extends Command
{
    protected $signature = 'vouchers:generate
                            {prefix : The prefix for voucher codes}
                            {count : Number of vouchers to generate}
                            {type : Voucher type (percentage, fixed_amount, free_shipping)}
                            {value : The voucher value (percentage or amount)}
                            {--expires= : Expiry date (Y-m-d)}';

    protected $description = 'Generate bulk voucher codes';

    public function handle(): int
    {
        $prefix = strtoupper($this->argument('prefix'));
        $count = (int) $this->argument('count');
        $type = $this->argument('type');
        $value = $this->argument('value');
        $expiresAt = $this->option('expires')
            ? \Carbon\Carbon::parse($this->option('expires'))
            : now()->addMonth();

        if ($count > 10000) {
            $this->error('Maximum 10000 vouchers can be generated at once.');
            return Command::FAILURE;
        }

        $this->info("Generating {$count} vouchers...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $vouchers = [];
        $generated = 0;

        while ($generated < $count) {
            $code = $prefix . strtoupper(Str::random(8));

            if (!Voucher::where('code', $code)->exists()) {
                $vouchers[] = [
                    'code' => $code,
                    'title' => "Bulk Voucher",
                    'type' => $type,
                    'value' => $value,
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $generated++;
                $bar->advance();
            }
        }

        // Insert in chunks
        foreach (array_chunk($vouchers, 1000) as $chunk) {
            Voucher::insert($chunk);
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully generated {$count} vouchers.");

        return Command::SUCCESS;
    }
}
```

---

## 8. Authorization Policies

### 8.1 Voucher Policy
```php
// app/Policies/VoucherPolicy.php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Voucher $voucher): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Voucher $voucher): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        return $user->role === 'admin';
    }

    public function duplicate(User $user, Voucher $voucher): bool
    {
        return $user->role === 'admin';
    }

    public function apply(User $user): bool
    {
        return true; // Any authenticated user can apply vouchers
    }

    public function viewAvailable(User $user): bool
    {
        return true;
    }
}
```

---

## 9. Routes

### 9.1 API Routes
```php
// routes/api.php
<?php

use App\Http\Controllers\Api\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Api\Customer\VoucherController as CustomerVoucherController;
use Illuminate\Support\Facades\Route;

// Public/Customer routes
Route::middleware('auth:sanctum')->group(function () {
    // Customer voucher routes
    Route::post('/vouchers/validate', [CustomerVoucherController::class, 'validate']);
    Route::post('/vouchers/apply', [CustomerVoucherController::class, 'apply']);
    Route::get('/vouchers/available', [CustomerVoucherController::class, 'available']);
    Route::get('/vouchers/my-usages', [CustomerVoucherController::class, 'myUsages']);
});

// Admin routes (require admin role)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/vouchers')->group(function () {
    Route::get('/', [AdminVoucherController::class, 'index']);
    Route::post('/', [AdminVoucherController::class, 'store']);
    Route::get('/{voucher}', [AdminVoucherController::class, 'show']);
    Route::put('/{voucher}', [AdminVoucherController::class, 'update']);
    Route::delete('/{voucher}', [AdminVoucherController::class, 'destroy']);
    Route::post('/{voucher}/duplicate', [AdminVoucherController::class, 'duplicate']);
    Route::patch('/{voucher}/toggle-status', [AdminVoucherController::class, 'toggleStatus']);
    Route::post('/bulk-generate', [AdminVoucherController::class, 'bulkGenerate']);
    Route::get('/export', [AdminVoucherController::class, 'export']);
});
```

---

## 10. Filament Admin Panel

### 10.1 Voucher Resource
```php
// app/Filament/Resources/VoucherResource.php
<?php

namespace App\Filament\Resources\Vouchers;

use App\Filament\Resources\Vouchers\Pages\CreateVoucher;
use App\Filament\Resources\Vouchers\Pages\EditVoucher;
use App\Filament\Resources\Vouchers\Pages\ListVouchers;
use App\Filament\Resources\Vouchers\Schemas\VoucherForm;
use App\Filament\Resources\Vouchers\Tables\VouchersTable;
use App\Models\Voucher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'code';

    protected static ?string $navigationLabel = 'Vouchers';

    protected static string | \UnitEnum | null $navigationGroup = 'Marketing';

    public static function form(Schema $schema): Schema
    {
        return VoucherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VouchersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVouchers::route('/'),
            'create' => CreateVoucher::route('/create'),
            'edit' => EditVoucher::route('/{record}/edit'),
        ];
    }
}
```

### 10.2 Voucher Form Schema
```php
// app/Filament/Resources/Vouchers/Schemas/VoucherForm.php
<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Split;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Voucher Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Info')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('code')
                                        ->required()
                                        ->maxLength(50)
                                        ->unique(Voucher::class, 'code')
                                        ->regex('/^[A-Z0-9_-]+$/'),
                                    TextInput::make('title')
                                        ->required()
                                        ->maxLength(255),
                                ]),
                                Textarea::make('description')
                                    ->maxLength(1000)
                                    ->rows(3),
                                Grid::make(2)->schema([
                                    Select::make('type')
                                        ->required()
                                        ->options([
                                            'percentage' => 'Percentage Discount',
                                            'fixed_amount' => 'Fixed Amount',
                                            'free_shipping' => 'Free Shipping',
                                            'buy_x_get_y' => 'Buy X Get Y',
                                        ]),
                                    TextInput::make('value')
                                        ->required()
                                        ->numeric()
                                        ->minValue(0)
                                        ->suffix('%')
                                        ->hidden(fn (callable $get) => $get('type') === 'free_shipping'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('max_discount')
                                        ->numeric()
                                        ->minValue(0)
                                        ->label('Max Discount Cap')
                                        ->helperText('Maximum discount amount for percentage vouchers'),
                                    TextInput::make('min_order_amount')
                                        ->numeric()
                                        ->minValue(0)
                                        ->label('Min Order Amount'),
                                ]),
                            ]),
                        Tabs\Tab::make('Usage Limits')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('usage_limit')
                                        ->numeric()
                                        ->minValue(1)
                                        ->label('Global Usage Limit')
                                        ->helperText('Total times this voucher can be used'),
                                    TextInput::make('usage_limit_per_user')
                                        ->numeric()
                                        ->minValue(1)
                                        ->label('Per User Limit'),
                                ]),
                            ]),
                        Tabs\Tab::make('Dates')
                            ->schema([
                                Grid::make(2)->schema([
                                    DatePicker::make('starts_at')
                                        ->required()
                                        ->label('Start Date'),
                                    DatePicker::make('expires_at')
                                        ->required()
                                        ->label('Expiry Date'),
                                ]),
                            ]),
                        Tabs\Tab::make('Targeting')
                            ->schema([
                                Select::make('target_type')
                                    ->options([
                                        'all' => 'All Products',
                                        'products' => 'Specific Products',
                                        'categories' => 'Specific Categories',
                                        'user_groups' => 'User Groups',
                                    ]),
                                Repeater::make('products')
                                    ->label('Products')
                                    ->hidden(fn (callable $get) => $get('target_type') !== 'products')
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Product')
                                            ->options(\App\Models\Package::pluck('title', 'id'))
                                            ->required(),
                                    ]),
                                Repeater::make('categories')
                                    ->label('Categories')
                                    ->hidden(fn (callable $get) => $get('target_type') !== 'categories')
                                    ->schema([
                                        Select::make('category_id')
                                            ->label('Category')
                                            ->options(\App\Models\Category::pluck('name', 'id'))
                                            ->required(),
                                    ]),
                                Select::make('target_user_group')
                                    ->label('User Group')
                                    ->hidden(fn (callable $get) => $get('target_type') !== 'user_groups')
                                    ->options(\App\Models\VoucherUserGroup::pluck('name', 'name')),
                                TextInput::make('min_user_account_age_days')
                                    ->numeric()
                                    ->minValue(0)
                                    ->label('Min Account Age (Days)'),
                            ]),
                        Tabs\Tab::make('Settings')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Active'),
                                Toggle::make('is_stackable')
                                    ->label('Allow Stacking')
                                    ->helperText('Allow this voucher to be combined with others'),
                                TextInput::make('max_stackable')
                                    ->numeric()
                                    ->minValue(0)
                                    ->label('Max Stackable Vouchers'),
                            ]),
                    ]),
            ]);
    }
}
```

### 10.3 Vouchers Table
```php
// app/Filament/Resources/Vouchers/Tables/VouchersTable.php
<?php

namespace App\Filament\Resources\Vouchers\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'percentage',
                        'success' => 'fixed_amount',
                        'warning' => 'free_shipping',
                        'info' => 'buy_x_get_y',
                    ]),
                TextColumn::make('value')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->type === 'percentage' ? "{$state}%" : "Rp " . number_format($state)
                    ),
                TextColumn::make('used_count')
                    ->label('Used')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->usage_limit ? "{$state} / {$record->usage_limit}" : $state
                    ),
                TextColumn::make('expires_at')
                    ->date('d M Y')
                    ->color(fn ($state) => $state->isPast() ? 'danger' : 'success'),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->updateState(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function ($record) {
                            $newVoucher = $record->replicate();
                            $newVoucher->code = strtoupper(\Illuminate\Support\Str::random(10));
                            $newVoucher->title = $record->title . ' (Copy)';
                            $newVoucher->used_count = 0;
                            $newVoucher->save();
                            $newVoucher->products()->sync($record->products->pluck('id'));
                            $newVoucher->categories()->sync($record->categories->pluck('id'));
                        }),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                //
            ]);
    }
}
```

### 10.4 Pages
```php
// app/Filament/Resources/Vouchers/Pages/ListVouchers.php
<?php

namespace App\Filament\Resources\Vouchers\Pages;

use App\Filament\Resources\Vouchers\VoucherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVouchers extends ListRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
```

---

## 11. Customer Frontend (Blade/Livewire)

### 11.1 Apply Voucher Component (Livewire)
```php
// app/Livewire/VoucherApply.php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\VoucherService;
use Illuminate\Support\Facades\Http;

class VoucherApply extends Component
{
    public string $code = '';
    public ?array $voucher = null;
    public bool $isValid = false;
    public bool $isApplied = false;
    public string $error = '';
    public float $discountAmount = 0;
    public float $orderAmount = 0;

    protected VoucherService $voucherService;

    public function mount(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    public function updatedCode()
    {
        $this->validateCode();
    }

    public function validateCode()
    {
        $this->reset(['error', 'voucher', 'isValid', 'discountAmount']);

        if (strlen($this->code) < 4) {
            return;
        }

        try {
            $result = $this->voucherService->validate(
                $this->code,
                auth()->id(),
                $this->orderAmount
            );

            if ($result['valid']) {
                $this->isValid = true;
                $this->voucher = [
                    'code' => $result['voucher']->code,
                    'title' => $result['voucher']->title,
                    'type' => $result['voucher']->type,
                    'value' => $result['voucher']->value,
                    'max_discount' => $result['voucher']->max_discount,
                ];

                $this->calculateDiscount();
            } else {
                $this->error = $result['error'];
                $this->isValid = false;
            }
        } catch (\Exception $e) {
            $this->error = 'Unable to validate voucher. Please try again.';
        }
    }

    public function calculateDiscount()
    {
        if (!$this->voucher || $this->orderAmount <= 0) {
            $this->discountAmount = 0;
            return;
        }

        $type = $this->voucher['type'];
        $value = (float) $this->voucher['value'];

        if ($type === 'percentage') {
            $discount = ($this->orderAmount * $value) / 100;
            if (!empty($this->voucher['max_discount'])) {
                $discount = min($discount, (float) $this->voucher['max_discount']);
            }
            $this->discountAmount = round($discount, 2);
        } elseif ($type === 'fixed_amount') {
            $this->discountAmount = min($value, $this->orderAmount);
        } else {
            $this->discountAmount = 0;
        }
    }

    public function applyVoucher()
    {
        $this->validateCode();

        if (!$this->isValid) {
            return;
        }

        // Store in session or emit event for checkout
        $this->dispatch('voucher-applied', [
            'code' => $this->code,
            'discount' => $this->discountAmount,
        ]);

        $this->isApplied = true;
    }

    public function removeVoucher()
    {
        $this->reset(['code', 'voucher', 'isValid', 'isApplied', 'discountAmount', 'error']);

        $this->dispatch('voucher-removed');
    }

    public function copyToClipboard()
    {
        $this->dispatch('copy-to-clipboard', $this->code);
    }

    public function render()
    {
        return view('livewire.voucher-apply');
    }
}
```

### 11.2 Blade View
```php
{{-- resources/views/livewire/voucher-apply.blade.php --}}
<div class="voucher-apply-component">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Have a Voucher?</h5>
        </div>
        <div class="card-body">
            @if($isApplied)
                <div class="alert alert-success d-flex align-items-center justify-content-between">
                    <div>
                        <strong>{{ $code }}</strong>
                        <span class="ms-2">- Rp {{ number_format($discountAmount, 0, ',', '.') }} off</span>
                    </div>
                    <button wire:click="removeVoucher" class="btn btn-sm btn-outline-danger">
                        Remove
                    </button>
                </div>
            @else
                <div class="input-group">
                    <input
                        type="text"
                        wire:model="code"
                        wire:input="updatedCode"
                        class="form-control"
                        placeholder="Enter voucher code"
                        aria-label="Voucher code"
                        {{-- Uppercase input --}}
                        oninput="this.value = this.value.toUpperCase()"
                    >
                    <button
                        wire:click="applyVoucher"
                        class="btn btn-primary"
                        type="button"
                        {{ !$isValid ? 'disabled' : '' }}
                    >
                        Apply
                    </button>
                </div>

                @if($code && strlen($code) >= 4)
                    <div class="mt-2">
                        @if($error)
                            <div class="alert alert-danger py-2 small">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $error }}
                            </div>
                        @elseif($isValid && $voucher)
                            <div class="alert alert-success py-2 small">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $voucher['title'] }}</strong>
                                        <span class="ms-2">
                                            @if($voucher['type'] === 'percentage')
                                                {{ $voucher['value'] }}% OFF
                                                @if($voucher['max_discount'])
                                                    (Max Rp {{ number_format($voucher['max_discount'], 0, ',', '.') }})
                                                @endif
                                            @elseif($voucher['type'] === 'fixed_amount')
                                                Rp {{ number_format($voucher['value'], 0, ',', '.') }} OFF
                                            @elseif($voucher['type'] === 'free_shipping')
                                                FREE SHIPPING
                                            @endif
                                        </span>
                                    </div>
                                    <button wire:click="copyToClipboard" class="btn btn-sm btn-outline-secondary" title="Copy code">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-muted small">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                Validating...
                            </div>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>

    @script
        <script>
            $wire.on('copy-to-clipboard', (code) => {
                navigator.clipboard.writeText(code);
                toastr.success('Voucher code copied!');
            });
        </script>
    @endscript
</div>
```

### 11.3 Available Vouchers Component
```php
// app/Livewire/AvailableVouchers.php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Voucher;
use Illuminate\Support\Collection;

class AvailableVouchers extends Component
{
    public Collection $vouchers;
    public bool $loading = true;

    protected function getVouchers()
    {
        return Voucher::active()
            ->hasAvailableUsage()
            ->notExpired()
            ->with(['products', 'categories'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    public function mount()
    {
        $this->loadVouchers();
    }

    public function loadVouchers()
    {
        $this->loading = true;
        $this->vouchers = $this->getVouchers();
        $this->loading = false;
    }

    public function applyVoucher(string $code)
    {
        $this->dispatch('apply-voucher-code', $code);
    }

    public function copyToClipboard(string $code)
    {
        $this->dispatch('copy-to-clipboard', $code);
    }

    public function render()
    {
        return view('livewire.available-vouchers');
    }
}
```

```php
{{-- resources/views/livewire/available-vouchers.blade.php --}}
<div class="available-vouchers">
    <h4 class="mb-3">Available Vouchers</h4>

    @if($loading)
        <div class="text-center py-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @elseif($vouchers->isEmpty())
        <p class="text-muted">No vouchers available at the moment.</p>
    @else
        <div class="row g-3">
            @foreach($vouchers as $voucher)
                <div class="col-12">
                    <div class="voucher-card border rounded p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <code class="bg-light px-2 py-1 rounded">{{ $voucher->code }}</code>
                                    <button
                                        wire:click="copyToClipboard('{{ $voucher->code }}')"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Copy code"
                                    >
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <h6 class="mt-2 mb-1">{{ $voucher->title }}</h6>
                                <p class="small text-muted mb-2">{{ $voucher->description }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary">
                                        @if($voucher->type === 'percentage')
                                            {{ $voucher->value }}% OFF
                                        @elseif($voucher->type === 'fixed_amount')
                                            Rp {{ number_format($voucher->value, 0, ',', '.') }} OFF
                                        @elseif($voucher->type === 'free_shipping')
                                            FREE SHIPPING
                                        @endif
                                    </span>
                                    @if($voucher->min_order_amount)
                                        <span class="badge bg-secondary">
                                            Min. Rp {{ number_format($voucher->min_order_amount, 0, ',', '.') }}
                                        </span>
                                    @endif
                                    @if($voucher->max_discount)
                                        <span class="badge bg-info">
                                            Max Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Expires: {{ $voucher->expires_at->format('d M Y') }}
                                </small>
                            </div>
                            <button
                                wire:click="applyVoucher('{{ $voucher->code }}')"
                                class="btn btn-primary btn-sm"
                            >
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
```

---

## 12. Edge Cases & Business Logic

### 12.1 Voucher Stacking Logic
```php
// app/Services/VoucherStackerService.php
<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Collection;

class VoucherStackerService
{
    public function canStack(array $voucherIds): bool
    {
        $vouchers = Voucher::whereIn('id', $voucherIds)->get();

        // Check if all vouchers are stackable
        if ($vouchers->contains('is_stackable', false)) {
            return false;
        }

        // Check max stackable limit
        $maxStackable = $vouchers->min('max_stackable') ?: 0;
        if (count($voucherIds) > $maxStackable) {
            return false;
        }

        // Ensure no conflicting target types
        $targetTypes = $vouchers->pluck('target_type')->unique();
        if ($targetTypes->count() > 1 && $targetTypes->contains('all')) {
            return false;
        }

        return true;
    }

    public function calculateStackedDiscount(array $vouchers, float $orderAmount): array
    {
        $totalDiscount = 0;
        $appliedVouchers = [];

        foreach ($vouchers as $voucher) {
            $remainingAmount = $orderAmount - $totalDiscount;

            if ($remainingAmount <= 0) {
                break;
            }

            $discount = app(VoucherCalculatorService::class)
                ->calculate($voucher, $remainingAmount);

            $totalDiscount += $discount;
            $appliedVouchers[] = [
                'voucher_id' => $voucher->id,
                'code' => $voucher->code,
                'discount' => $discount,
            ];
        }

        return [
            'total_discount' => round($totalDiscount, 2),
            'final_amount' => round($orderAmount - $totalDiscount, 2),
            'applied_vouchers' => $appliedVouchers,
        ];
    }
}
```

### 12.2 Partial Refund Handler
```php
// app/Services/RefundService.php
<?php

namespace App\Services;

use App\Models\VoucherUsage;

class PartialRefundHandler
{
    public function handle(int $bookingId, float $refundAmount): bool
    {
        $usage = VoucherUsage::where('booking_id', $bookingId)
            ->whereIn('status', ['applied', 'used'])
            ->first();

        if (!$usage) {
            return false;
        }

        return app(VoucherService::class)->processPartialRefund(
            $usage->id,
            $refundAmount
        );
    }
}
```

---

## 13. Testing

### 13.1 Unit Tests - Voucher Model
```php
// tests/Unit/Models/VoucherTest.php
<?php

namespace Tests\Unit\Models;

use App\Models\Voucher;
use App\Models\User;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    use RefreshDatabase;

    public function test_voucher_code_is_uppercase(): void
    {
        $voucher = Voucher::create([
            'code' => 'testcode',
            'title' => 'Test Voucher',
            'type' => 'percentage',
            'value' => 10,
            'starts_at' => now(),
            'expires_at' => now()->addWeek(),
        ]);

        $this->assertEquals('TESTCODE', $voucher->code);
    }

    public function test_expired_voucher_detection(): void
    {
        $voucher = Voucher::create([
            'code' => 'EXPIRED',
            'title' => 'Expired Voucher',
            'type' => 'percentage',
            'value' => 10,
            'starts_at' => now()->subWeek(),
            'expires_at' => now()->subDay(),
        ]);

        $this->assertTrue($voucher->isExpired());
    }

    public function test_usage_limit_check(): void
    {
        $voucher = Voucher::create([
            'code' => 'LIMITED',
            'title' => 'Limited Voucher',
            'type' => 'percentage',
            'value' => 10,
            'usage_limit' => 5,
            'used_count' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addWeek(),
        ]);

        $this->assertFalse($voucher->hasAvailableUsage());
    }

    public function test_user_usage_limit_check(): void
    {
        $voucher = Voucher::create([
            'code' => 'USERLIMIT',
            'title' => 'User Limit Voucher',
            'type' => 'percentage',
            'value' => 10,
            'usage_limit_per_user' => 2,
            'starts_at' => now(),
            'expires_at' => now()->addWeek(),
        ]);

        $user = User::factory()->create();

        // Simulate user has used the voucher twice
        \App\Models\VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'user_id' => $user->id,
            'discount_amount' => 10,
            'order_amount_before_discount' => 100,
            'order_amount_after_discount' => 90,
            'status' => 'used',
        ]);

        \App\Models\VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'user_id' => $user->id,
            'discount_amount' => 15,
            'order_amount_before_discount' => 150,
            'order_amount_after_discount' => 135,
            'status' => 'used',
        ]);

        $this->assertTrue($voucher->hasUserReachedLimit($user->id));
    }
}
```

### 13.2 Feature Tests - API
```php
// tests/Feature/Api/VoucherApiTest.php
<?php

namespace Tests\Feature\Api;

use App\Models\Voucher;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VoucherApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_validate_voucher(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $voucher = Voucher::create([
            'code' => 'VALID10',
            'title' => '10% Off',
            'type' => 'percentage',
            'value' => 10,
            'starts_at' => now(),
            'expires_at' => now()->addWeek(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/vouchers/validate', [
            'code' => 'VALID10',
            'order_amount' => 100,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'voucher' => [
                    'code' => 'VALID10',
                ],
            ]);
    }

    public function test_customer_cannot_apply_expired_voucher(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $voucher = Voucher::create([
            'code' => 'EXPIRED',
            'title' => 'Expired',
            'type' => 'percentage',
            'value' => 10,
            'starts_at' => now()->subWeek(),
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/vouchers/validate', [
            'code' => 'EXPIRED',
            'order_amount' => 100,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
                'error' => 'Voucher has expired',
            ]);
    }

    public function test_customer_can_apply_voucher(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'BK' . rand(1000, 9999),
            'pax_count' => 2,
            'total_price' => 500000,
            'status' => 'pending',
        ]);

        $voucher = Voucher::create([
            'code' => 'APPLYME',
            'title' => 'Apply Me',
            'type' => 'percentage',
            'value' => 10,
            'starts_at' => now(),
            'expires_at' => now()->addWeek(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/vouchers/apply', [
            'code' => 'APPLYME',
            'order_amount' => 500000,
            'booking_id' => $booking->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'discount_amount' => 50000,
            ]);

        $this->assertDatabaseHas('voucher_usages', [
            'voucher_id' => $voucher->id,
            'user_id' => $user->id,
            'booking_id' => $booking->id,
        ]);

        $voucher->refresh();
        $this->assertEquals(1, $voucher->used_count);
    }
}
```

### 13.3 Race Condition Test
```php
// tests/Feature/VoucherRaceConditionTest.php
<?php

namespace Tests\Feature;

use App\Models\Voucher;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class VoucherRaceConditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_voucher_usage_respects_limit(): void
    {
        // Create voucher with limit of 1
        $voucher = Voucher::create([
            'code' => 'RACETEST',
            'title' => 'Race Test',
            'type' => 'fixed_amount',
            'value' => 10000,
            'usage_limit' => 1,
            'starts_at' => now(),
            'expires_at' => now()->addDay(),
            'is_active' => true,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $booking1 = Booking::create([
            'user_id' => $user1->id,
            'booking_code' => 'BK001',
            'pax_count' => 1,
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        $booking2 = Booking::create([
            'user_id' => $user2->id,
            'booking_code' => 'BK002',
            'pax_count' => 1,
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        // First request should succeed
        $response1 = $this->postJson('/api/vouchers/apply', [
            'code' => 'RACETEST',
            'order_amount' => 100000,
            'booking_id' => $booking1->id,
        ]);

        $response1->assertStatus(200);

        // Second request should fail due to usage limit
        $response2 = $this->postJson('/api/vouchers/apply', [
            'code' => 'RACETEST',
            'order_amount' => 100000,
            'booking_id' => $booking2->id,
        ]);

        $response2->assertStatus(422);

        // Verify only 1 usage was recorded
        $this->assertEquals(1, $voucher->fresh()->used_count);
    }
}
```

---

## 14. Security

### 14.1 Rate Limiting
```php
// app/Providers/AppServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('voucher-validation', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'error' => 'Too many validation attempts. Please try again later.',
                    ], 429);
                });
        });

        RateLimiter::for('voucher-application', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json([
                        'error' => 'Too many application attempts. Please try again later.',
                    ], 429);
                });
        });
    }
}
```

### 14.2 Apply Rate Limiting to Routes
```php
// routes/api.php
Route::middleware(['auth:sanctum', 'throttle:voucher-validation'])->group(function () {
    Route::post('/vouchers/validate', [...]);
});

Route::middleware(['auth:sanctum', 'throttle:voucher-application'])->group(function () {
    Route::post('/vouchers/apply', [...]);
});
```

### 14.3 Audit Logging Middleware
```php
// app/Http/Middleware/LogVoucherActivity.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogVoucherActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log voucher-related activities
        if (in_array($request->path(), ['api/vouchers/validate', 'api/vouchers/apply'])) {
            Log::channel('voucher')->info('Voucher Activity', [
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
                'endpoint' => $request->path(),
                'code' => $request->code,
                'status' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }
}
```

---

## 15. Caching Strategy

```php
// app/Services/VoucherCacheService.php
<?php

namespace App\Services;

use App\Models\Voucher;
use Illuminate\Support\Facades\Cache;

class VoucherCacheService
{
    public const CACHE_TTL = 300; // 5 minutes

    public function getAvailableVouchers(int $userId): array
    {
        return Cache::remember("user:{$userId}:available_vouchers", self::CACHE_TTL, function () {
            return Voucher::active()
                ->hasAvailableUsage()
                ->notExpired()
                ->get()
                ->toArray();
        });
    }

    public function getVoucherByCode(string $code): ?Voucher
    {
        return Cache::remember("voucher:code:{$code}", self::CACHE_TTL, function () use ($code) {
            return Voucher::where('code', strtoupper($code))->first();
        });
    }

    public function invalidateVoucher(int $voucherId): void
    {
        $voucher = Voucher::find($voucherId);
        if ($voucher) {
            Cache::forget("voucher:code:{$voucher->code}");
        }
    }

    public function invalidateUserVouchers(int $userId): void
    {
        Cache::forget("user:{$userId}:available_vouchers");
    }
}
```

---

## 16. Implementation Priority

| Phase | Components | Description |
|-------|-----------|-------------|
| **1** | Database | Create all migrations and models |
| **2** | Core Services | VoucherService, Validator, Calculator |
| **3** | Admin API | CRUD endpoints for admin |
| **4** | Admin UI | Filament resource pages |
| **5** | Customer API | Validate/Apply endpoints |
| **6** | Customer UI | Livewire components |
| **7** | Business Logic | Stacking, refunds, edge cases |
| **8** | Security | Rate limiting, audit logging |
| **9** | Testing | Unit and feature tests |

---

## 17. Summary

This plan provides a comprehensive, production-ready implementation of a Voucher/Coupon feature in Laravel 12 with:

- **Database**: Complete schema with soft deletes, proper indexes, and relationships
- **Models**: Eloquent models with relationships, scopes, accessors, and mutators
- **Services**: Clean separation of concerns (validation, calculation, stacking)
- **API**: RESTful controllers with proper resource classes
- **Admin**: Filament 5.0 integration with full CRUD
- **Frontend**: Livewire components for real-time validation
- **Security**: Rate limiting, authorization policies, audit logging
- **Testing**: Unit, feature, and race condition tests

The implementation follows Laravel best practices, SOLID principles, and maintains consistency with the existing codebase patterns.
