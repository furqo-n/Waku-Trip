<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Cloudinary\Cloudinary;

class UploadImageToCloudinary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public string $modelClass,
        public int $modelId,
        public string $imageColumn = 'image_path',
        public string $cloudinaryFolder = 'packages',
    ) {}

    public function handle(): void
    {
        $model = $this->modelClass::find($this->modelId);

        if (!$model) {
            Log::warning("UploadImageToCloudinary: Model {$this->modelClass}#{$this->modelId} not found, skipping.");
            return;
        }

        $localPath = $model->{$this->imageColumn};

        if (empty($localPath)) {
            Log::info("UploadImageToCloudinary: No image path for {$this->modelClass}#{$this->modelId}, skipping.");
            return;
        }

        // Already a Cloudinary URL — nothing to do
        if (str_starts_with($localPath, 'http://') || str_starts_with($localPath, 'https://')) {
            $model->update(['upload_status' => 'uploaded']);
            return;
        }

        // Check the file exists locally
        if (!Storage::disk('public')->exists($localPath)) {
            Log::error("UploadImageToCloudinary: Local file not found: {$localPath}");
            $model->update(['upload_status' => 'failed']);
            return;
        }

        try {
            // Get the absolute file path
            $absolutePath = Storage::disk('public')->path($localPath);

            // Use Cloudinary SDK directly — it expects a file path
            $cloudinary = app(Cloudinary::class);
            
            // Force the public ID to match the local filename so our accessor URL construction works
            $info = pathinfo($localPath);
            $publicId = $info['filename']; 

            $result = $cloudinary->uploadApi()->upload($absolutePath, [
                'folder' => $this->cloudinaryFolder, // e.g., "packages"
                'public_id' => $publicId,            // e.g., "01KHW..."
                'use_filename' => true,
                'unique_filename' => false,
                'overwrite' => true,
                'resource_type' => 'image',
            ]);

            // Keep image_path as the local relative path (so Filament can still display it)
            // The model accessor will serve the Cloudinary URL on the frontend
            $model->update([
                'upload_status' => 'uploaded',
            ]);

            Log::info("UploadImageToCloudinary: Successfully uploaded {$localPath} to Cloudinary for {$this->modelClass}#{$this->modelId} → {$result['secure_url']}");
        } catch (\Throwable $e) {
            Log::error("UploadImageToCloudinary: Failed for {$this->modelClass}#{$this->modelId}: {$e->getMessage()}");
            $model->update(['upload_status' => 'failed']);
            throw $e; // Re-throw so the queue can retry
        }
    }

    public function failed(\Throwable $exception): void
    {
        $model = $this->modelClass::find($this->modelId);
        if ($model) {
            $model->update(['upload_status' => 'failed']);
        }
        Log::error("UploadImageToCloudinary: Permanently failed for {$this->modelClass}#{$this->modelId}: {$exception->getMessage()}");
    }
}
