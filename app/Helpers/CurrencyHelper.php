<?php

if (!function_exists('convert_currency')) {
    function convert_currency($amount)
    {
        return app(\App\Services\CurrencyService::class)->format($amount);
    }
}
