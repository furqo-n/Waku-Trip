<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\PackageImage;
use App\Models\Category;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define your packages here
        $packages = [
            [
                'title'         => 'Kyoto Private Retreat',
                'slug'          => 'kyoto-private-retreat',
                'description'   => 'Experience the ancient capital in exclusive luxury. Stay in a private machiya, dine with geisha, and visit temples after hours.',
                'location_text' => 'Kyoto, Japan',
                'duration_days' => 5,
                'group_size'    => '2-4 People',
                'language'      => 'English, Japanese',
                'is_guided'     => true,
                'base_price'    => 4500.00,
                'type'          => 'private', // 'open', 'private', or 'activity'
                'season'        => 'Autumn',
                'is_trending'   => true,
                'destination_id'=> null, // Optional: Add Destination ID if needed
                
                // Related Data
                'categories'    => ['Cultural', 'Luxury', 'History'],
                'images'        => [
                    [
                        'image_path' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=2070&auto=format&fit=crop',
                        'is_primary' => true
                    ],
                    [
                        'image_path' => 'https://images.unsplash.com/photo-1528360983277-13d9012356ee?q=80&w=2070&auto=format&fit=crop',
                        'is_primary' => false
                    ]
                ]
            ],
            [
                'title'         => 'Tokyo Foodie Adventure',
                'slug'          => 'tokyo-foodie-adventure',
                'description'   => 'A deep dive into Tokyo\'s culinary scene. Sushi masterclasses, izakaya hopping, and wagyu tasting.',
                'location_text' => 'Tokyo, Japan',
                'duration_days' => 1,
                'group_size'    => 'Small Group',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 350.00,
                'type'          => 'activity',
                'season'        => 'All Year',
                'is_trending'   => false,
                'destination_id'=> null,

                'categories'    => ['Foodie', 'Urban'],
                'images'        => [
                    [
                        'image_path' => 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?q=80&w=2070&auto=format&fit=crop',
                        'is_primary' => true
                    ]
                ]
            ],
            // Add more packages here...
        ];

        foreach ($packages as $data) {
            // Extract relations
            $categories = $data['categories'] ?? [];
            $images = $data['images'] ?? [];
            
            // Remove relations from data array before creating Package
            unset($data['categories'], $data['images']);

            // Create or Update Package
            $package = Package::updateOrCreate(
                ['slug' => $data['slug']], // Check duplicate by slug
                $data
            );

            // Sync Categories
            if (!empty($categories)) {
                // Find category IDs by name (ensure categories exist first)
                $categoryIds = Category::whereIn('name', $categories)->pluck('id');
                $package->relatedCategories()->sync($categoryIds);
            }

            // Add Images
            // First, clear existing images if you want to refresh them, or just add new ones.
            // For seeding, let's delete old ones to prevent duplicates if running seeder multiple times.
            $package->images()->delete();
            
            foreach ($images as $img) {
                $package->images()->create($img);
            }
        }
    }
}
