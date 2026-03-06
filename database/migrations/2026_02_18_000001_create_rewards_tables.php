<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reward items available for redemption
        Schema::create('reward_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('points_cost');
            $table->string('badge')->nullable();           // "Limited Time", "Exclusive", "New"
            $table->string('badge_class')->nullable();      // "limited", "exclusive", "new"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Point transactions log
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('reward_item_id')->nullable()->constrained('reward_items')->onDelete('set null');
            $table->enum('type', ['earned', 'redeemed', 'bonus'])->default('earned');
            $table->string('description');
            $table->integer('points');  // positive for earned/bonus, negative for redeemed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('reward_items');
    }
};
