<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use App\Models\Currency;

class CurrencyService
{
    private $currencies = null;

    /**
     * Get all active currencies, cached for performance.
     */
    public function getCurrencies()
    {
        if ($this->currencies !== null) {
            return $this->currencies;
        }

        try {
            return $this->currencies = Cache::remember('all_currencies', 60, function () {
                try {
                    $result = Currency::where('is_active', true)->get()->keyBy('code');
                    return $result->isNotEmpty() ? $result : $this->getCurrenciesFromConfig();
                } catch (\Throwable $e) {
                    return $this->getCurrenciesFromConfig();
                }
            });
        } catch (\Throwable $e) {
            return $this->currencies = $this->getCurrenciesFromConfig();
        }
    }

    /**
     * Fallback when DB/cache unavailable: build collection from config.
     */
    protected function getCurrenciesFromConfig()
    {
        $currencies = config('currency.currencies', []);
        $collection = collect();
        foreach ($currencies as $code => $data) {
            $collection->put($code, (object) array_merge($data, ['code' => $code]));
        }
        return $collection;
    }

    /**
     * Get details for a specific currency code.
     */
    public function getCurrencyDetails($code)
    {
        return $this->getCurrencies()->get($code);
    }

    /**
     * Get the current currency code from session or default config.
     */
    public function getCurrentCurrency()
    {
        $default = config('currency.default', 'USD');
        $currency = Session::get('currency', $default);
        
        // validate existence
        if (!$this->getCurrencies()->has($currency)) {
            return $default;
        }
        
        return $currency;
    }

    /**
     * Set the current currency in the session.
     */
    public function setCurrency($currencyCode)
    {
        if ($this->getCurrencies()->has($currencyCode)) {
            Session::put('currency', $currencyCode);
        }
    }

    /**
     * Convert an amount from the base currency to the target currency.
     * Assumes base currency is USD (rate 1.0).
     */
    public function convert($amount, $targetCurrency = null)
    {
        $targetCurrency = $targetCurrency ?: $this->getCurrentCurrency();
        $currency = $this->getCurrencyDetails($targetCurrency);
        $rate = $currency ? $currency->rate : 1.0;

        return $amount * $rate;
    }

    /**
     * Format the amount with the currency symbol.
     */
    public function format($amount, $currencyCode = null)
    {
        $currencyCode = $currencyCode ?: $this->getCurrentCurrency();
        $currency = $this->getCurrencyDetails($currencyCode);

        if (!$currency) {
            return '$' . number_format($amount, 2);
        }

        $convertedAmount = $amount * $currency->rate;

        // Formatting rules
        if ($currencyCode === 'IDR' || $currencyCode === 'JPY') {
             // No decimals for IDR and JPY usually
            $formattedNumber = number_format($convertedAmount, 0, ',', '.');
        } else {
            // 2 decimals for USD, EUR
            $formattedNumber = number_format($convertedAmount, 2, '.', ',');
        }

        return sprintf($currency->format, $formattedNumber);
    }
}
