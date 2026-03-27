<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, \App\Traits\HasMedia;

    protected array $mediaFieldMaps = [
        'primary_image' => 'primary_image',
    ];

    protected $fillable = [
        'destination_id',
        'title',
        'slug',
        'description',
        'location_text',
        'duration_days',
        'group_size',
        'language',
        'is_guided',
        'base_price',
        'type',
        'season',
        'is_trending',
        'primary_image',     // Virtual field for HasMedia
        'gallery_images',    // Virtual field for HasMedia if needed
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function images()
    {
        return $this->hasMany(PackageImage::class);
    }

    public function inclusions()
    {
        return $this->hasMany(PackageInclusion::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }

    public function tripSchedules()
    {
        return $this->hasMany(TripSchedule::class);
    }

    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            PackageCategory::class,
            'package_id', // Foreign key on package_categories table...
            'id', // Foreign key on categories table...
            'id', // Local key on packages table...
            'category_id' // Local key on package_categories table...
        );
    }
    
    // Better simplified via BelongsToMany if pivot is defined, but HasManyThrough or direct use of PackageCategory works too.
    // Let's stick to standard BelongsToMany for Categories if we didn't use an explicit pivot model for logic, but we have PackageCategory.
    // Actually, a simple BelongsToMany is cleaner if we just want related categories.
    public function relatedCategories()
    {
        return $this->belongsToMany(Category::class, 'package_categories');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the primary image URL (HasMedia first, fallback to legacy PackageImage).
     */
    protected function primaryImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $url = $this->getFirstMediaUrl('primary_image');
                if ($url) {
                    return $url;
                }
                $legacy = $this->images->where('is_primary', true)->first();
                return $legacy?->image_url ?? 'https://via.placeholder.com/800x600?text=No+Image';
            },
        );
    }
    public function skipsFlightTickets(): bool
    {
        $type = strtolower($this->type ?? '');
        $skipKeywords = ['activity', 'private'];
        
        if (in_array($type, $skipKeywords)) {
            return true;
        }

        // Check if any related category name contains the skip keywords
        return $this->relatedCategories()
            ->where(function($query) use ($skipKeywords) {
                foreach ($skipKeywords as $keyword) {
                    $query->orWhere('name', 'LIKE', "%{$keyword}%");
                }
            })->exists();
    }
}
