<?php

use App\Models\AppSetting;
use App\Models\SiteIcon;

if (!function_exists('app_setting')) {
    /**
     * Get an application setting (specifically media URLs).
     *
     * @param string $key The media field map key.
     * @param string|null $fallback A fallback URL.
     * @return string
     */
    function app_setting(string $key, ?string $fallback = null)
    {
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
    }
}

if (!function_exists('site_icon')) {
    /**
     * Retrieve raw inline SVG markup for a given icon key.
     * Use {!! site_icon('key') !!} in Blade to render with zero network latency.
     *
     * @param string $key     The unique icon identifier (e.g. 'facebook_icon').
     * @param string $default Fallback SVG markup if key is not found.
     * @return string         Raw SVG markup.
     */
    function site_icon(string $key, string $default = ''): string
    {
        try {
            return SiteIcon::svgFor($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}
