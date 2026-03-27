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

        if ($request->has('product_ids')) {
            $voucher->products()->sync($request->product_ids);
        }

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

    protected function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (Voucher::where('code', $code)->exists());

        return $code;
    }
}
