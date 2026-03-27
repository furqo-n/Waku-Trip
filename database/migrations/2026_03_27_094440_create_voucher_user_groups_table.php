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
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('voucher_user_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_user_group_id')->constrained('voucher_user_groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('member_since')->useCurrent();
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
