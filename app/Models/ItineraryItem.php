<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class ItineraryItem extends Model
{
    use \App\Traits\HasMedia;

    protected $fillable = ['itinerary_id', 'content', 'order', 'image_path', 'upload_status'];
    protected $appends = ['image_url'];

    protected array $mediaFieldMaps = [
        'image_path' => 'primary_image',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $mediaUrl = $this->getFirstMediaUrl('primary_image');
                if ($mediaUrl) {
                    return $mediaUrl;
                }

                $value = $this->image_path;
                if (empty($value)) {
                    return null;
                }

                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    return $value;
                }

                if ($this->upload_status === 'uploaded') {
                    $cloudName = config('filesystems.disks.cloudinary.cloud');
                    if ($cloudName) {
                        return "https://res.cloudinary.com/{$cloudName}/image/upload/{$value}";
                    }
                }

                return asset('storage/' . $value);
            },
        );
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
