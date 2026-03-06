<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('group_size')->nullable()->after('duration_days'); // e.g., "Max 10"
            $table->string('language')->nullable()->after('group_size'); // e.g., "English", "Multilingual"
            $table->boolean('is_guided')->default(true)->after('language'); // true = Guided, false = Self-Guided
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['group_size', 'language', 'is_guided']);
        });
    }
};
