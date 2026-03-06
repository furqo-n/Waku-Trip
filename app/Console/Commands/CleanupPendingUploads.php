<?php

namespace App\Console\Commands;

use App\Jobs\UploadImageToCloudinary;
use App\Models\Itinerary;
use App\Models\PackageImage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupPendingUploads extends Command
{
    protected $signature = 'cloudinary:cleanup-pending
                            {--retry : Retry failed uploads instead of deleting}
                            {--max-age=60 : Max age in minutes for pending/failed records}';

    protected $description = 'Clean up or retry pending/failed Cloudinary uploads';

    public function handle(): int
    {
        $maxAge = (int) $this->option('max-age');
        $retry = $this->option('retry');
        $cutoff = Carbon::now()->subMinutes($maxAge);

        $this->info("Looking for pending/failed uploads older than {$maxAge} minutes...");

        // PackageImages
        $staleImages = PackageImage::whereIn('upload_status', ['pending', 'failed'])
            ->where('updated_at', '<', $cutoff)
            ->get();

        $this->info("Found {$staleImages->count()} stale PackageImage records.");

        foreach ($staleImages as $image) {
            if ($retry) {
                $this->info("  Re-queuing PackageImage #{$image->id}...");
                $image->update(['upload_status' => 'pending']);
                UploadImageToCloudinary::dispatch(
                    PackageImage::class,
                    $image->id,
                    'image_path',
                    'packages',
                );
            } else {
                $this->warn("  Deleting orphaned PackageImage #{$image->id} (path: {$image->image_path})");
                // Delete local file if it still exists
                if ($image->image_path && !str_starts_with($image->image_path, 'http')) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        }

        // Itineraries
        $staleItineraries = Itinerary::whereIn('upload_status', ['pending', 'failed'])
            ->where('updated_at', '<', $cutoff)
            ->get();

        $this->info("Found {$staleItineraries->count()} stale Itinerary records.");

        foreach ($staleItineraries as $itinerary) {
            if ($retry) {
                $this->info("  Re-queuing Itinerary #{$itinerary->id}...");
                $itinerary->update(['upload_status' => 'pending']);
                UploadImageToCloudinary::dispatch(
                    Itinerary::class,
                    $itinerary->id,
                    'image_path',
                    'itineraries',
                );
            } else {
                $this->warn("  Cleaning up Itinerary #{$itinerary->id} image (path: {$itinerary->image_path})");
                if ($itinerary->image_path && !str_starts_with($itinerary->image_path, 'http')) {
                    Storage::disk('public')->delete($itinerary->image_path);
                }
                // Don't delete itinerary record, just clear the image
                $itinerary->update(['image_path' => null, 'upload_status' => 'uploaded']);
            }
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
