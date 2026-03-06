<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Services\CurrencyService;

class SetCurrency
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function handle(Request $request, Closure $next)
    {
        $currentCurrency = $this->currencyService->getCurrentCurrency();
        
        // Share currency data with all views
        View::share('currentCurrency', $currentCurrency);
        View::share('currencySymbol', config("currency.currencies.{$currentCurrency}.symbol"));

        return $next($request);
    }
}
