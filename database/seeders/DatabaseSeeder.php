<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Destination;
use App\Models\Category;
use App\Models\Package;
use App\Models\PackageImage;
use App\Models\TripSchedule;
use App\Models\Itinerary;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Destinations
        $destinations = [
            ['name' => 'Honshu', 'slug' => 'honshu', 'description' => 'Main island including Tokyo, Kyoto, Osaka.'],
            ['name' => 'Hokkaido', 'slug' => 'hokkaido', 'description' => 'Northern island famous for winter sports and dairy.'],
            ['name' => 'Kyushu', 'slug' => 'kyushu', 'description' => 'Southern island known for hot springs.'],
            ['name' => 'Tokyo', 'slug' => 'tokyo', 'description' => 'The vibrant capital.'],
            ['name' => 'Kyoto', 'slug' => 'kyoto', 'description' => 'Cultural capital.'],
            ['name' => 'Osaka', 'slug' => 'osaka', 'description' => 'Kitchen of Japan.'],
        ];

        foreach ($destinations as $dest) {
            Destination::firstOrCreate(['slug' => $dest['slug']], $dest);
        }

        // 2. Categories (Interests)
        $categories = [
            ['name' => 'Foodie', 'slug' => 'foodie', 'icon' => 'ramen_dining'],
            ['name' => 'Shrines', 'slug' => 'shrines', 'icon' => 'castle'],
            ['name' => 'Nature', 'slug' => 'nature', 'icon' => 'hiking'],
            ['name' => 'Shopping', 'slug' => 'shopping', 'icon' => 'shopping_bag'],
            ['name' => 'Onsen', 'slug' => 'onsen', 'icon' => 'hot_tub'],
            ['name' => 'Rail Pass', 'slug' => 'rail-pass', 'icon' => 'train'],
            ['name' => 'Anime', 'slug' => 'anime', 'icon' => 'auto_awesome'],
            ['name' => 'History', 'slug' => 'history', 'icon' => 'temple_hindu'],
            ['name' => 'Cultural', 'slug' => 'cultural', 'icon' => 'temple_buddhist'],
            ['name' => 'Culinary', 'slug' => 'culinary', 'icon' => 'restaurant'],
            ['name' => 'Sports', 'slug' => 'sports', 'icon' => 'sports_kabaddi'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Packages (Tours)
        
        // --- TOUR 1: Kyoto & Osaka: The Golden Route (from tour_detail.blade.php) ---
        $kyotoDest = Destination::where('slug', 'honshu')->first();
        $goldenRoute = Package::create([
            'destination_id' => $kyotoDest->id,
            'title' => 'Kyoto & Osaka: The Golden Route',
            'slug' => 'kyoto-osaka-golden-route',
            'description' => 'Embark on an unforgettable journey through the heart of Japan\'s cultural capital, Kyoto, and the vibrant culinary powerhouse of Osaka.',
            'location_text' => 'Kyoto & Osaka, Japan',
            'duration_days' => 7,
            'group_size' => 'Max 10',
            'language' => 'English',
            'is_guided' => true,
            'base_price' => 2400.00,
            'type' => 'open',
            'season' => 'Autumn',
            'is_trending' => true,
        ]);
        
        // Images (Placeholders from current blade)
        PackageImage::create(['package_id' => $goldenRoute->id, 'image_path' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=2070&auto=format&fit=crop', 'is_primary' => true]);
        PackageImage::create(['package_id' => $goldenRoute->id, 'image_path' => 'https://images.unsplash.com/photo-1590559902693-b71569424e66?q=80&w=2070&auto=format&fit=crop']);
        
        // Itinerary
        Itinerary::create(['package_id' => $goldenRoute->id, 'day_number' => 1, 'title' => 'Arrival in Kyoto & Welcome Dinner', 'description' => 'Arrive at Kansai International Airport. Transfer to boutique hotel. Kaiseki welcome dinner.', 'image_path' => 'https://images.unsplash.com/photo-1545569384-63454dd69cb7?q=80&w=2070&auto=format&fit=crop']);
        Itinerary::create(['package_id' => $goldenRoute->id, 'day_number' => 2, 'title' => 'Temples of Northern Kyoto', 'description' => 'Visit Kinkaku-ji and Ryoan-ji. Stroll through Arashiyama Bamboo Grove.', 'image_path' => 'https://images.unsplash.com/photo-1624253321171-1be53e12f5f4?q=80&w=1887&auto=format&fit=crop']);
        Itinerary::create(['package_id' => $goldenRoute->id, 'day_number' => 3, 'title' => 'Transfer to Osaka & Street Food', 'description' => 'Train to Osaka. Dotonbori street food safari.', 'image_path' => 'https://images.unsplash.com/photo-1590253230537-8b63435e9541?q=80&w=2008&auto=format&fit=crop']);
        
        // Categories
        $goldenRoute->relatedCategories()->attach(Category::whereIn('slug', ['history', 'foodie', 'cultural'])->pluck('id'));
        
        
        // Inclusions for Golden Route
        $goldenInclusions = [
            ['item' => '7 Nights Accommodation (4-star hotels)', 'is_included' => true],
            ['item' => 'Daily Breakfast', 'is_included' => true],
            ['item' => 'Professional English-speaking Guide', 'is_included' => true],
            ['item' => 'All Local Transport (Train/Bus)', 'is_included' => true],
            ['item' => 'Airport Transfers', 'is_included' => true],
            ['item' => 'Entrance Fees to Temples & Shrines', 'is_included' => true],
            ['item' => 'International Flights', 'is_included' => false],
            ['item' => 'Travel Insurance', 'is_included' => false],
            ['item' => 'Personal Expenses', 'is_included' => false],
            ['item' => 'Lunch & Dinner (unless specified)', 'is_included' => false],
        ];

        foreach ($goldenInclusions as $inc) {
            \App\Models\PackageInclusion::create(array_merge(['package_id' => $goldenRoute->id], $inc));
        }

        // Schedule
        TripSchedule::create(['package_id' => $goldenRoute->id, 'start_date' => '2023-11-14', 'end_date' => '2023-11-21', 'price' => 2400.00, 'quota' => 10, 'available_seats' => 8]);

        // --- TOUR 2: Golden Route Classic (from index.blade.php) ---
        $classic = Package::create([
            'destination_id' => $kyotoDest->id,
            'title' => 'Golden Route Classic',
            'slug' => 'golden-route-classic',
            'description' => 'The absolute classic tour covering Tokyo, Kyoto, and Osaka in 10 days.',
            'location_text' => 'Tokyo • Kyoto • Osaka',
            'duration_days' => 10,
            'group_size' => 'Max 12',
            'language' => 'English',
            'is_guided' => true,
            'base_price' => 2400.00,
            'type' => 'open',
            'is_trending' => true,
        ]);
        PackageImage::create(['package_id' => $classic->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuByUgVSkWagyMIp8dQ0SYs88IxQvEurNP22kcyaSzVnZChVbW9AWRJb43pBqCB4E3IkgbKJmkFBhud8IeYZGLpSt7c6gnhJvSehYO19vGqHrOeTc1_1ButHv6OOSEwT45dnQXV5hWXys4PMpadusmbVS2QDQnJ_SdzSqvKNwuvD2u9DsmABTc3TOKaMiw6grDqgnDeWx1ALeTG7k3cHjcZH09wrPTEJ-XkYam3zRUWTyN3xkdWyLL6NGePOT-kMq37BEiQyEZjKDQo', 'is_primary' => true]);
        
        // --- TOUR 3: Hokkaido Winter Drift (from index.blade.php) ---
        $hokkaidoDest = Destination::where('slug', 'hokkaido')->first();
        $hokkaido = Package::create([
            'destination_id' => $hokkaidoDest->id,
            'title' => 'Hokkaido Winter Drift',
            'slug' => 'hokkaido-winter-drift',
            'description' => 'Experience the best powder snow in the world.',
            'location_text' => 'Sapporo • Otaru • Niseko',
            'duration_days' => 7,
            'group_size' => 'Max 8',
            'language' => 'English',
            'is_guided' => true,
            'base_price' => 1800.00,
            'type' => 'open',
            'season' => 'Winter',
            'is_trending' => true,
        ]);
        PackageImage::create(['package_id' => $hokkaido->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBbdBrgmuNfroHCcfwVJnvF-GylHKRfCIhFL5ghT9mtAbHRMUiIsjWewlinVY8WD9G6qPYlhstWy4AdR7RrWMsxVqnoFGd0cAq95GeVDeTm09V38ExTCPj52Q1QAFNzPINoYwJtMs8s3eqKG2tLG0yEThty_0uQWXAQMVMKC31KmY9T06CgJQJOyPzIXEBfl4Ci_YRZH_ar9-TsY1i_ANUiI7LQ9iFDn-dn5qOX_O_ScVM1JlbeGupXBpaFqPn_SVhO-g39_HJvZ8Y', 'is_primary' => true]);
        $hokkaido->relatedCategories()->attach(Category::whereIn('slug', ['nature', 'onsen'])->pluck('id'));

        // --- TOUR 4: Nara Cultural Dive (from index.blade.php) ---
        $nara = Package::create([
            'destination_id' => $kyotoDest->id,
            'title' => 'Nara Cultural Dive',
            'slug' => 'nara-cultural-dive',
            'description' => 'Deep dive into ancient capital Nara.',
            'location_text' => 'Kyoto • Nara • Uji',
            'duration_days' => 5,
            'group_size' => 'Max 10',
            'language' => 'English',
            'is_guided' => true,
            'base_price' => 1200.00,
            'type' => 'open',
            'is_trending' => true,
        ]);
        PackageImage::create(['package_id' => $nara->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuArpbblWJpuOZvwgSnrohAHll2x0EvkyVt0MGvF9ZW8vsZEcargWCJNYfLN6NnY9nfLr7RA6OG1rLeVx1ikmA2KWjM7OAHvjHLsZdcOjU_Q3n0Sa1HHjh6s69csP5EcZmlxSB0Z64Od3yyXKLTGRbiHnsjLuuuWCYZ2i1_2tYnHgXZ3iMA_NYw_jJ1YIiP16XhJVPB61ozg7JOA_NKQzLb99GjDbYNMkAiSmhskmlKSBGoWpLST5tRvy1HiFFQi3fonQiF2N8fvlRg', 'is_primary' => true]);
        $nara->relatedCategories()->attach(Category::whereIn('slug', ['history', 'cultural', 'shrines'])->pluck('id'));

        // --- TOUR 5: Kyoto Zen Heritage (from planned.blade.php) ---
        $zen = Package::create([
            'destination_id' => $kyotoDest->id,
            'title' => 'Kyoto Zen Heritage',
            'slug' => 'kyoto-zen-heritage',
            'description' => 'Focus on meditation and Zen gardens.',
            'location_text' => 'Gion District, Kyoto',
            'duration_days' => 3,
            'group_size' => 'Max 6',
            'language' => 'English',
            'is_guided' => true,
            'base_price' => 1200.00,
            'type' => 'open',
            'season' => 'Autumn',
        ]);
        PackageImage::create(['package_id' => $zen->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCMtXF3AjRaO8CKOEkvg4VdSY_l7Lit34Fgz6sCnsM0U0Fdnc0aaIV4UvYILSYi_W-43gFvJzy1_stwIy2lEFSkTdDo3j0-2koHDAvekwlPiHlGfKCIE6CncmCQSRC3gi5U4sgAqPHy5i5_Dx3Zb0CCuPg2eWB4DgKz948gDSeRhjY1k2oM0JWnVCh5whH4WqHUnYPYDXpR2gDaMLncsZW_b80qJ-cNQo6yCjZ3-p7edgR0YHCzUvwpf8wvuQMhNLtPPR-JSgT2dzk', 'is_primary' => true]);
        $zen->relatedCategories()->attach(Category::whereIn('slug', ['cultural', 'history'])->pluck('id'));
        TripSchedule::create(['package_id' => $zen->id, 'start_date' => '2023-10-15', 'end_date' => '2023-10-17', 'price' => 1200.00, 'quota' => 8, 'available_seats' => 5]);

        // --- TOUR 6: Tokyo Cyber Night (from planned.blade.php) ---
        $cyber = Package::create([
            'destination_id' => Destination::where('slug', 'tokyo')->first()->id ?? $kyotoDest->id,
            'title' => 'Tokyo Cyber Night',
            'slug' => 'tokyo-cyber-night',
            'description' => 'Explore the neon-lit streets of Shibuya and Shinjuku.',
            'location_text' => 'Shibuya & Shinjuku',
            'duration_days' => 1,
            'group_size' => 'Private',
            'language' => 'English',
            'is_guided' => true,
            'base_price' => 450.00,
            'type' => 'private',
        ]);
        PackageImage::create(['package_id' => $cyber->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC9iNvx6P25KvpdoIqEaMmzntIxxjeA1ejAJrwW3pECr1icff2kO1P4a_knQ2NK3bAdgSAIsXTGqAdBvheJddu0sPL1GBqrbq5ntqkEtXWLnvHuhqf8-MkwaTPHefPSqoHcXXgE-Q_7Qfagq0NX5gLe9yNNz7aP13WAUCspfneVvqdyD_ls_uTlhx7lUuut52p7UQR20kcgU2DXFpdcH5DjpwAzRzwsJhC1YF3Zuy32hyMk3nVbQ4p5lM_0Vuq5SSbYAqD0wbx4iwo', 'is_primary' => true]);
        $cyber->relatedCategories()->attach(Category::whereIn('slug', ['foodie', 'anime'])->pluck('id'));

        // --- ACTIVITIES / EXPERIENCES (from index.blade.php) ---
        $tea = Package::create([
            'destination_id' => Destination::where('slug', 'kyoto')->first()->id ?? $kyotoDest->id,
            'title' => 'Private Tea Ceremony',
            'slug' => 'private-tea-ceremony',
            'description' => 'Authentic tea ceremony in a private teahouse.',
            'location_text' => 'Kyoto',
            'duration_days' => 1,
            'base_price' => 150.00,
            'type' => 'activity',
        ]);
        PackageImage::create(['package_id' => $tea->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDBKx7rEbxsfIC8tBzGmpj8fJljNIhAws86OOh_brzlUiNhoo_64Dy7irwX1x93pMWMgHjvglv20MF8L9nWOmufXLXTnMh4zGDtVgjVG-wvpyQ0TLc8vC3Uf3JL3H1oEFgYNsV4EglmF95xkn1nNT1lGASBhAAyQgV9dPqsbz1NvSBo2FBvZnkBsYpg11iU4YfT2Vvj3y5YonrO4YnahgoCrmFhD1dhL6F3_d2YFpwIPzoIPWRr0Gk5kfuEGuyYEYE-syPKxsY_k54', 'is_primary' => true]);
        $tea->relatedCategories()->attach(Category::whereIn('slug', ['cultural'])->pluck('id'));

        $sumo = Package::create([
            'destination_id' => Destination::where('slug', 'tokyo')->first()->id ?? $kyotoDest->id,
            'title' => 'Sumo Stable Visit',
            'slug' => 'sumo-stable-visit',
            'description' => 'Watch Sumo wrestlers train up close.',
            'location_text' => 'Tokyo',
            'duration_days' => 1,
            'base_price' => 220.00,
            'type' => 'activity',
        ]);
        PackageImage::create(['package_id' => $sumo->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDj64Wu8RYB4sYvVEJCGShwUGRaD1t7BTZhZ8KwYLuj8GCvq1gqlV1fSnxRQFODEa9p0ZC3vbV0GaDbpwV0LoOY81_7OTxHuGHNqGv-hi1ZH-4P396S0bDPGgohSJjNHjs7kCwQl_5K1qYwsCcJ9AEdoRIx5SyzwR2nY6FXiLI1OsnAwzMUrpr0SfabZI0DusyUw3krcRHQ5dnSaCryjBKDVBAN1FlazvmPP8lRw1vQ49ABZ-poORfJyXA8mDWLWQSeL7rE9sx9Xtw', 'is_primary' => true]);
        $sumo->relatedCategories()->attach(Category::whereIn('slug', ['sports'])->pluck('id'));

        $omakase = Package::create([
            'destination_id' => Destination::where('slug', 'tokyo')->first()->id ?? $kyotoDest->id,
            'title' => 'Omakase Experience',
            'slug' => 'omakase-experience',
            'description' => 'Premium Sushi Omakase in Ginza.',
            'location_text' => 'Ginza, Tokyo',
            'duration_days' => 1,
            'base_price' => 300.00,
            'type' => 'activity',
        ]);
        PackageImage::create(['package_id' => $omakase->id, 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDj8C7UnefcjFVTRcvbwdBq_wJ2ICAEWc6VSIlwT9_mLuh56VJJJKi8A7JRdeecUUHdlVGuHm-GT45UdKjQNnU8SbKe9eLRMhM901_DGoghygPb9J-bFkgm_IC7KZRlQrz2d4o4oXWqJ8w12H4iP1zvekckuSH0YSAQcI732BCNuDRZKps8grO869HBxkHvCvJB1chhw3pRIgBpVfvQ-ctFK5l--OVAzqfhgpTQbByW1kf_QBtgUEsEwjJbeqghsvezcGgC-bLdgKc', 'is_primary' => true]);
        $omakase->relatedCategories()->attach(Category::whereIn('slug', ['culinary', 'foodie'])->pluck('id'));

        // --- USERS & REVIEWS ---
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'traveler@example.com'],
            ['name' => 'Global Traveler', 'password' => bcrypt('password')]
        );

        // Reviews for Golden Route
        Review::create([
            'user_id' => $user->id,
            'package_id' => $goldenRoute->id,
            'rating' => 5,
            'comment' => 'Absolutely amazing experience! The guide was knowledgeable and the itinerary was perfect.',
            'is_approved' => true
        ]);
        Review::create([
            'user_id' => $user->id,
            'package_id' => $goldenRoute->id,
            'rating' => 4,
            'comment' => 'Great tour, but the pace was a bit fast.',
            'is_approved' => true
        ]);

        // Reviews for Hokkaido Winter Drift
        Review::create([
            'user_id' => $user->id,
            'package_id' => $hokkaido->id,
            'rating' => 5,
            'comment' => 'Best powder snow ever! The onsen after skiing was divine.',
            'is_approved' => true
        ]);

        // Reviews for Nara Cultural Dive
        Review::create([
            'user_id' => $user->id,
            'package_id' => $nara->id,
            'rating' => 2,
            'comment' => 'Creating memories that will last a lifetime. Nara is magical.',
            'is_approved' => true
        ]);
        Review::create([
            'user_id' => $user->id,
            'package_id' => $nara->id,
            'rating' => 5,
            'comment' => 'Feeding the deer was so much fun! Highly reliable tour operator.',
            'is_approved' => true
        ]);
        
        // --- Add Future Schedules ---
        $this->call(DummyScheduleSeeder::class);

    }
}
