<?php

use Illuminate\Support\Facades\Cache;
use App\Models\AppSetting;
use App\Models\SiteIcon;

if (!function_exists('app_setting')) {
    /**
     * Get an application setting (specifically media URLs).
     * Result is cached for 24 hours.
     *
     * @param string $key The media field map key.
     * @param string|null $fallback A fallback URL.
     * @return string
     */
    function app_setting(string $key, ?string $fallback = null)
    {
        $cacheKey = 'app_setting_' . $key;

        return Cache::remember($cacheKey, 86400, function () use ($key, $fallback) {
            try {
                $settings = AppSetting::instance();
                $url = $settings->getFirstMediaUrl($key);

                if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Failsafe if DB isn't migrated yet
            }

            return $fallback ?? "https://via.placeholder.com/800x600?text=" . urlencode(ucwords(str_replace('_', ' ', $key)));
        });
    }
}

if (!function_exists('site_icon')) {
    /**
     * Retrieve raw inline SVG markup for a given icon key.
     * All icons are preloaded in a single query and cached for 24 hours.
     *
     * @param string $key     The unique icon identifier (e.g. 'facebook_icon').
     * @param string $default Fallback SVG markup if key is not found.
     * @return string         Raw SVG markup.
     */
    function site_icon(string $key, string $default = ''): string
    {
        try {
            // Load all icons in a single cached query instead of one per call
            $icons = Cache::remember('all_site_icons', 86400, function () {
                return SiteIcon::all()->keyBy('key');
            });

            return $icons->get($key)?->svg_code ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
