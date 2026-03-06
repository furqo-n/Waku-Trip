<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use \App\Traits\HasMedia;

    protected $fillable = [
        'site_name',
        'default_tour_image',
        'default_news_image',
        'default_avatar',
        'login_bg_1',
        'login_bg_2',
        'login_bg_3',
        'register_bg',
        'home_hero_bg',
    ];

    protected array $mediaFieldMaps = [
        'default_tour_image' => 'default_tour_image',
        'default_news_image' => 'default_news_image',
        'default_avatar'     => 'default_avatar',
        'login_bg_1'         => 'login_bg_1',
        'login_bg_2'         => 'login_bg_2',
        'login_bg_3'         => 'login_bg_3',
        'register_bg'        => 'register_bg',
        'home_hero_bg'       => 'home_hero_bg',
    ];

    /**
     * Helper to get instance (singleton pattern)
     */
    public static function instance()
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
