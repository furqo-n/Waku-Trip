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
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('package_images', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->string('image_url')->nullable();
        });

        Schema::table('package_images', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
        });
    }
};
