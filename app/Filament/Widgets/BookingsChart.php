<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class BookingsChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Bookings Trend';

    protected function getData(): array
    {
        $months = [];
        $pendingData = [];
        $paidData = [];
        $confirmedData = [];
        $cancelledData = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = \Carbon\Carbon::create()->day(1)->month($i)->format('M');
            
            $pendingData[] = \App\Models\Booking::whereYear('created_at', now()->year)->whereMonth('created_at', $i)->where('status', 'pending')->count();
            $paidData[] = \App\Models\Booking::whereYear('created_at', now()->year)->whereMonth('created_at', $i)->where('status', 'paid')->count();
            $confirmedData[] = \App\Models\Booking::whereYear('created_at', now()->year)->whereMonth('created_at', $i)->where('status', 'confirmed')->count();
            $cancelledData[] = \App\Models\Booking::whereYear('created_at', now()->year)->whereMonth('created_at', $i)->where('status', 'cancelled')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pending',
                    'data' => $pendingData,
                    'borderColor' => '#F59E0B', // Amber
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Confirmed',
                    'data' => $confirmedData,
                    'borderColor' => '#3B82F6', // Blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Paid',
                    'data' => $paidData,
                    'borderColor' => '#10B981', // Emerald
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Cancelled',
                    'data' => $cancelledData,
                    'borderColor' => '#EF4444', // Red
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
