<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class RewardItem extends Model
{
    use HasMedia;

    protected array $mediaFieldMaps = [
        'image' => 'reward_image',
    ];

    protected $fillable = [
        'title',
        'description',
        'image',
        'points_cost',
        'badge',
        'badge_class',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the image URL (HasMedia first, fallback to the stored image path/url).
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return $this->getFirstMediaUrl('reward_image') ?: $value;
            },
        );
    }

    public function transactions()
    {
        return $this->hasMany(PointTransaction::class);
    }
}
