<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Destination extends Model
{
    use HasFactory, \App\Traits\HasMedia;

    protected array $mediaFieldMaps = [
        'image_url' => 'primary_image',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMedia('primary_image')?->public_id ?: null,
        );
    }

    protected $fillable = ['name', 'slug', 'image_url', 'description'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
