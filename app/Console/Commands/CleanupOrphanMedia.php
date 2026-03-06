<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupOrphanMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:cleanup-orphans';
    protected $description = 'Deletes unassigned images from Cloudinary and the local database to prevent storage bloat.';

    public function handle()
    {
        $this->info('Starting orphan media cleanup...');

        // Get all media asset IDs currently mapped to any model
        $usedMediaIds = \Illuminate\Support\Facades\DB::table('mediables')->pluck('media_asset_id')->toArray();

        // Find media assets older than 24 hours that are NOT in the used list
        // and also process any 'temporary' assets (though the relation check is the ultimate source of truth)
        $orphans = \App\Models\MediaAsset::where('created_at', '<', now()->subHours(24))
            ->whereNotIn('id', $usedMediaIds)
            ->get();

        if ($orphans->isEmpty()) {
            $this->info('No orphan media found.');
            return;
        }

        $count = 0;
        foreach ($orphans as $orphan) {
            try {
                // Delete actual file from Cloudinary 
                \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::destroy($orphan->public_id);
                // Delete the DB record
                $orphan->delete();
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to delete orphan {$orphan->public_id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully deleted {$count} orphan media assets.");
    }
}
