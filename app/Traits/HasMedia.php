<?php

namespace App\Traits;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMedia
{
    /**
     * Temporary storage for mapped media fields during the saving process.
     */
    public array $pendingMediaUploads = [];

    /**
     * Boot the trait to automatically sync media assets when a mapped field is saved.
     */
    protected static function bootHasMedia()
    {
        static::saving(function ($model) {
            // Intercept virtual media fields to prevent SQL "Column not found" errors
            if (isset($model->mediaFieldMaps) && is_array($model->mediaFieldMaps)) {
                $rawAttributes = $model->getAttributes();
                foreach ($model->mediaFieldMaps as $field => $collection) {
                    if (array_key_exists($field, $rawAttributes)) {
                        if ($model->isDirty($field) || !$model->exists) {
                            // Store the raw path before unsetting it, bypassing accessors
                            $model->pendingMediaUploads[$field] = $rawAttributes[$field];
                        }
                        
                        // Unset from the actual attributes array so Eloquent doesn't query it
                        $model->offsetUnset($field);
                    }
                }
            }
        });

        static::saved(function ($model) {
            if (!empty($model->pendingMediaUploads)) {
                $cloudName = config('filesystems.disks.cloudinary.cloud');
                
                foreach ($model->pendingMediaUploads as $field => $path) {
                    $collection = $model->mediaFieldMaps[$field] ?? 'default';
                    
                    // Always detach old media for single-image mappings
                    $model->media()->wherePivot('collection_name', $collection)->detach();
                    
                    if (!empty($path)) {
                        // Safe offline URL generation
                        $url = "https://res.cloudinary.com/{$cloudName}/image/upload/{$path}";
                        
                         // Create or update the Media Asset
                        $media = MediaAsset::firstOrCreate(
                            ['public_id' => $path],
                            [
                                'url' => $url,
                                'status' => 'permanent'
                            ]
                        );
                        
                        // IMPORTANT: For single-image fields, we must detach old ones first
                        // so that getFirstMedia() always returns the latest/correct one.
                        $model->media()->wherePivot('collection_name', $collection)->detach();
                        
                        // Sync it to this model's collection
                        $model->media()->syncWithoutDetaching([
                            $media->id => ['collection_name' => $collection]
                        ]);
                    }
                }
                // Clear pending uploads after successful sync
                $model->pendingMediaUploads = [];
            }
        });
    }

    /**
     * Get all of the model's media assets.
     */
    public function media(): MorphToMany
    {
        return $this->morphToMany(MediaAsset::class, 'mediable')
                    ->withPivot('collection_name')
                    ->withTimestamps();
    }

    /**
     * Retrieve media for a specific collection.
     */
    public function getMedia(string $collectionName = 'default')
    {
        return $this->media->where('pivot.collection_name', $collectionName);
    }
    
    /**
     * Get the first media asset for a collection.
     */
    public function getFirstMedia(string $collectionName = 'default')
    {
        return $this->media->where('pivot.collection_name', $collectionName)->first();
    }
    
    /**
     * Get the URL of the first media asset, or a fallback.
     */
    public function getFirstMediaUrl(string $collectionName = 'default', string $fallback = ''): string
    {
        $media = $this->getFirstMedia($collectionName);
        return $media ? $media->getOptimizedUrl() : $fallback;
    }
}
