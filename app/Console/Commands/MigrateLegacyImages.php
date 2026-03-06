<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateLegacyImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:migrate-legacy';
    protected $description = 'Migrates old string image paths into the new polymorphic MediaAsset relationships.';

    public function handle()
    {
        $this->info('Starting legacy media migration...');

        // 1. News
        $newsCount = 0;
        foreach (\App\Models\News::whereNotNull('image_path')->get() as $news) {
            $this->createMedia($news, $news->image_path, 'primary_image');
            $newsCount++;
        }
        $this->info("Migrated {$newsCount} News images.");

        // 2. Destinations
        $destCount = 0;
        foreach (\App\Models\Destination::whereNotNull('image_url')->get() as $dest) {
             // Destination uses image_url instead of image_path usually
            $this->createMedia($dest, $dest->image_url, 'primary_image');
            $destCount++;
        }
        $this->info("Migrated {$destCount} Destination images.");

        // 3. Package Images
        $pkgCount = 0;
        foreach (\App\Models\PackageImage::whereNotNull('image_path')->get() as $pi) {
            $package = $pi->package;
            if ($package) {
                 $collection = $pi->is_primary ? 'primary_image' : 'gallery';
                 $this->createMedia($package, $pi->image_path, $collection);
                 $pkgCount++;
            }
        }
        $this->info("Migrated {$pkgCount} Package images.");

        // 4. Itineraries
        $itinCount = 0;
        foreach (\App\Models\Itinerary::whereNotNull('image_path')->get() as $itin) {
            $this->createMedia($itin, $itin->image_path, 'primary_image');
            $itinCount++;
        }
        $this->info("Migrated {$itinCount} Itinerary images.");

        // 5. Users (Avatar)
        $userCount = 0;
        foreach (\App\Models\User::whereNotNull('avatar')->get() as $user) {
            $this->createMedia($user, $user->avatar, 'avatar');
            $userCount++;
        }
        $this->info("Migrated {$userCount} User avatars.");

        $this->info('Migration completed successfully!');
    }

    private function createMedia($model, $path, $collection)
    {
        // Don't migrate external placehoder URLs or empty paths
        if (empty($path) || str_starts_with($path, 'http')) {
             return;
        }

        try {
            $cloudName = config('filesystems.disks.cloudinary.cloud');
            $media = \App\Models\MediaAsset::firstOrCreate(
                ['public_id' => $path],
                ['url' => "https://res.cloudinary.com/{$cloudName}/image/upload/{$path}", 'status' => 'permanent']
            );

            // Ensure models have the HasMedia trait, so we can access media(), 
            // OR we can manually insert into mediables if they don't have the trait yet.
            \Illuminate\Support\Facades\DB::table('mediables')->updateOrInsert([
                'media_asset_id' => $media->id,
                'mediable_type' => get_class($model),
                'mediable_id' => $model->id,
                'collection_name' => $collection,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->warn("Failed to migrate asset: {$path} - " . $e->getMessage());
        }
    }
}
