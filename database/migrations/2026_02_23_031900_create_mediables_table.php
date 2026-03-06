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
        Schema::create('mediables', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->morphs('mediable'); // creates mediable_type and mediable_id
            $table->string('collection_name')->default('default')->index(); // e.g primary_image, gallery
            $table->timestamps();
            
            // A model should not have the exact same media attached to the same collection multiple times
            $table->unique(['media_asset_id', 'mediable_type', 'mediable_id', 'collection_name'], 'mediable_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mediables');
    }
};
