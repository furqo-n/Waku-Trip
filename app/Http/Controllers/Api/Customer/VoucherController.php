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
