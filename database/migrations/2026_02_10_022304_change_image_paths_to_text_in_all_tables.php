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
        Schema::table('destinations', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });

        Schema::table('package_images', function (Blueprint $table) {
            $table->text('image_path')->change();
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->text('image_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('image_url')->nullable()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('image_url')->nullable()->change();
        });

        Schema::table('package_images', function (Blueprint $table) {
            $table->string('image_path')->change();
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change();
        });
    }
};
