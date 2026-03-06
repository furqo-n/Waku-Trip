<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_images', function (Blueprint $table) {
            $table->string('upload_status')->default('uploaded')->after('is_primary');
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->string('upload_status')->default('uploaded')->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('package_images', function (Blueprint $table) {
            $table->dropColumn('upload_status');
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn('upload_status');
        });
    }
};
