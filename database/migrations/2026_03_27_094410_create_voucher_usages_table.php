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
