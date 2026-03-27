<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('vouchers', 'code')->ignore($this->voucher),
                'regex:/^[A-Z0-9_-]+$/',
            ],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['sometimes', Rule::in(['percentage', 'fixed_amount'])],
            'value' => ['sometimes', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['sometimes', 'date'],
            'expires_at' => ['sometimes', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
            'is_stackable' => ['boolean'],
            'max_stackable' => ['nullable', 'integer', 'min:0'],
            'target_type' => ['sometimes', Rule::in(['all', 'products', 'categories', 'user_groups'])],
            'target_user_group' => ['nullable', 'string', 'max:100'],
            'min_user_account_age_days' => ['nullable', 'integer', 'min:0'],
            'product_ids' => ['array', 'nullable'],
            'product_ids.*' => ['integer', 'exists:packages,id'],
            'category_ids' => ['array', 'nullable'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }
}
