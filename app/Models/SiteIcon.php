<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteIcon extends Model
{
    protected $fillable = ['key', 'name', 'svg_code'];

    /**
     * Retrieve SVG code by key, or return a default string.
     */
    public static function svgFor(string $key, string $default = ''): string
    {
        return static::where('key', $key)->value('svg_code') ?? $default;
    }
}
