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
        // 1. Masters
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Honshu", "Hokkaido", "Kyoto"
            $table->string('slug')->unique();
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Foodie", "Nature", "Anime"
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // Material symbol name
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        // 2. Products (Tours/Packages)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->onDelete('set null'); // Primary region
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description'); // Main marketing text
            $table->string('location_text')->nullable(); // e.g., "Tokyo • Kyoto • Osaka"
            $table->integer('duration_days'); 
            $table->decimal('base_price', 10, 2); // Display price "From $X"
            $table->enum('type', ['open', 'private', 'activity'])->default('open');
            $table->string('season')->nullable(); // "Spring", "Winter"
            $table->boolean('is_trending')->default(false);
            $table->timestamps();
        });

        Schema::create('package_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('package_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });

        Schema::create('package_inclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('item'); // e.g., "Daily Breakfast"
            $table->boolean('is_included')->default(true); // true = included, false = excluded
            $table->timestamps();
        });

        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->integer('day_number');
            $table->string('title');
            $table->text('description');
            $table->string('image_path')->nullable(); // Specific image for that day
            $table->timestamps();
        });

        // 3. Inventory (Schedules)
        Schema::create('trip_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2); // Specific price for this date
            $table->integer('quota')->default(10);
            $table->integer('available_seats')->default(10);
            $table->enum('status', ['available', 'full', 'cancelled', 'completed'])->default('available');
            $table->timestamps();
        });

        // 4. Sales & Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('trip_schedule_id')->nullable()->constrained('trip_schedules')->onDelete('set null'); // Nullable for custom/private manual bookings
            $table->string('booking_code')->unique(); // e.g., TRP-2023-001
            $table->integer('pax_count');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'paid', 'cancelled', 'completed'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('name');
            $table->string('passport_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // "credit_card", "bank_transfer"
            $table->string('transaction_id')->nullable();
            $table->string('payment_proof')->nullable(); // URL to uploaded image
            $table->enum('status', ['pending', 'verified', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 5. Custom Requests (Planner)
        Schema::create('private_trip_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Can be guest? Best to require login or store email
            $table->string('email')->nullable(); // For guests
            $table->string('name')->nullable();
            $table->json('destinations')->nullable(); // ["Honshu", "Hokkaido"]
            $table->json('interests')->nullable(); // ["Foodie", "Anime"]
            $table->string('start_date_preference')->nullable();
            $table->integer('duration_days')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->integer('pax_count');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'contacted', 'quoted', 'booked', 'closed'])->default('pending');
            $table->timestamps();
        });

        // 6. Social
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Run the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('private_trip_requests');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_passengers');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('trip_schedules');
        Schema::dropIfExists('itineraries');
        Schema::dropIfExists('package_inclusions');
        Schema::dropIfExists('package_categories');
        Schema::dropIfExists('package_images');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('destinations');
    }
};
