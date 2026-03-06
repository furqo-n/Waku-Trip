<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class CompletedBookingsList extends TableWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Completed Bookings')
            ->query(fn (): Builder => Booking::query()->where('status', 'completed')->latest())
            ->columns([
                TextColumn::make('booking_code')->searchable(),
                TextColumn::make('user.name')->label('Customer'),
                TextColumn::make('tripSchedule.package.title')->label('Package'),
                TextColumn::make('pax_count')->label('Pax')->numeric(),
                TextColumn::make('total_price')
                    ->money(config('currency.default', 'USD'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
