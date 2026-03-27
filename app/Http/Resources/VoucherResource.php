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
