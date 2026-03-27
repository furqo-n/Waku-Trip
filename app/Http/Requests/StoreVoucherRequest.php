<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
            'type' => ['required', Rule::in(['percentage', 'fixed_amount'])],
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
