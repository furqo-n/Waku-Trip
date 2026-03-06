<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 4; // Display in 4 columns since we have 4 stats
    }

    protected function getStats(): array
    {
        $totalRevenue = \App\Models\Booking::whereIn('status', ['paid', 'confirmed'])->sum('total_price');
        $totalBookings = \App\Models\Booking::count();
        $totalPackages = \App\Models\Package::count();
        $totalCustomers = \App\Models\User::where('role', '!=', 'admin')->count();

        // Check if a specific default currency is needed, fallback to USD logic
        $currencyCode = config('currency.default', 'USD');
        $currencySymbol = config("currency.currencies.{$currencyCode}.symbol", '$');

        return [
            Stat::make('Total Revenue', $currencySymbol . ' ' . number_format($totalRevenue, 2))
                ->description('From confirmed & paid bookings')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Total Bookings', $totalBookings)
                ->description('All time bookings')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Active Packages', $totalPackages)
                ->description('Available for sale')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('warning'),
            Stat::make('Customers', $totalCustomers)
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
