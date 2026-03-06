<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class News extends Model
{
    use HasFactory, \App\Traits\HasMedia;

    protected array $mediaFieldMaps = [
        'image_path' => 'primary_image',
    ];

    protected function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMedia('primary_image')?->public_id ?: null,
        );
    }

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image_path',
        'author',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'date',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function rootComments()
    {
        return $this->comments()->whereNull('parent_id')->with('replies.user');
    }
}
