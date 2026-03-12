<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MediaAsset extends Model
{
    use HasUuids;

    protected $fillable = [
        'public_id',
        'url',  
        'status',
    ];

    /**
     * Get Cloudinary highly optimized image URL.
     * Injects f_auto, q_auto, and dimensions directly into the URL string.
     */
    public function getOptimizedUrl(?int $width = null, ?int $height = null, string $crop = 'fill')
    {
        $url = $this->url;

        $cloudName = config('filesystems.disks.cloudinary.cloud');

        // Ensure it's a Cloudinary URL belonging to this app
        if (!str_contains($url, 'res.cloudinary.com/' . $cloudName . '/image/upload/')) {
            return $url;
        }

        $transformations = ['f_auto', 'q_auto'];

        if ($width) {
            $transformations[] = "w_{$width}";
        }
        if ($height) {
            $transformations[] = "h_{$height}";
        }
        if ($width || $height) {
            $transformations[] = "c_{$crop}";
        }

        $transformationString = implode(',', $transformations);

        // Inject transformations right after /image/upload/
        $url = str_replace('/image/upload/', "/image/upload/{$transformationString}/", $url);
        
        // Force the extension to strictly be .webp to make it obvious
        $url = preg_replace('/\.(jpe?g|png|bmp|gif)$/i', '.webp', $url);

        return $url;
    }
}
