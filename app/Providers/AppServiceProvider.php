<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        $this->app->singleton(\App\Services\CurrencyService::class, function ($app) {
            return new \App\Services\CurrencyService();
        });

        \Illuminate\Pagination\Paginator::useBootstrapFive();
        \App\Models\Booking::observe(\App\Observers\BookingObserver::class);
        \App\Models\Currency::observe(\App\Observers\CurrencyObserver::class);

        // Share currency data with specific frontend views only (avoiding Filament/Admin overhead)
        $frontendViews = [
            'index',
            'tour_detail',
            'planned_list',
            'private_list',
            'news*',
            'aboutus',
            'custome',
            'contact',
            'partials.header',
            'payment.*',
            'profile.*',
            'layouts.*'
        ];

        \Illuminate\Support\Facades\View::composer($frontendViews, function ($view) {
            $currencyService = app(\App\Services\CurrencyService::class);
            $currentCurrency = $currencyService->getCurrentCurrency() ?? 'USD';
            $currencyDetails = $currencyService->getCurrencyDetails($currentCurrency);
            
            // Fallback if not found (though getCurrentCurrency handles validation)
            if (!$currencyDetails) {
                 $currencyDetails = $currencyService->getCurrencyDetails('USD');
            }

            $view->with('currentCurrency', $currentCurrency);
            $view->with('currencySymbol', optional($currencyDetails)->symbol ?? '$');
            $view->with('currencyRate', optional($currencyDetails)->rate ?? 1.0);
        });
    }
}
