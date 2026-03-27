<?php

use App\Http\Controllers\Api\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Api\Customer\VoucherController as CustomerVoucherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/vouchers/validate', [CustomerVoucherController::class, 'validate']);
    Route::post('/vouchers/apply', [CustomerVoucherController::class, 'apply']);
    Route::get('/vouchers/available', [CustomerVoucherController::class, 'available']);
    Route::get('/vouchers/my-usages', [CustomerVoucherController::class, 'myUsages']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/vouchers')->group(function () {
    Route::get('/', [AdminVoucherController::class, 'index']);
    Route::post('/', [AdminVoucherController::class, 'store']);
    Route::get('/{voucher}', [AdminVoucherController::class, 'show']);
    Route::put('/{voucher}', [AdminVoucherController::class, 'update']);
    Route::delete('/{voucher}', [AdminVoucherController::class, 'destroy']);
    Route::post('/{voucher}/duplicate', [AdminVoucherController::class, 'duplicate']);
    Route::patch('/{voucher}/toggle-status', [AdminVoucherController::class, 'toggleStatus']);
});
