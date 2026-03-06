<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class PackageImage extends Model
{
    use HasFactory;

    protected $fillable = ['package_id', 'image_path', 'is_primary', 'upload_status'];
    protected $appends = ['image_url'];

    /**
     * Compute full URL for display.
     * Use ->image_url instead of ->image_path in frontend.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $value = $this->image_path;
                if (empty($value)) {
                    return null;
                }

                // Already a full URL (legacy Cloudinary or external)
                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    return $value;
                }

                // If uploaded to Cloudinary, construct the URL from the path
                if ($this->upload_status === 'uploaded') {
                    $cloudName = config('filesystems.disks.cloudinary.cloud');
                    if ($cloudName) {
                        // path is like "packages/filename.jpg" → Cloudinary public ID
                        return "https://res.cloudinary.com/{$cloudName}/image/upload/{$value}";
                    }
                }

                // Serve from local storage (pending, failed, or no Cloudinary config)
                return asset('storage/' . $value);
            },
        );
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
