<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('trip_schedule_id')
                    ->options(\App\Models\TripSchedule::with('package')->get()->mapWithKeys(fn ($schedule) => [
                        $schedule->id => $schedule->full_title
                    ]))
                    ->searchable()
                    ->required(),
                TextInput::make('booking_code')
                    ->default(fn () => strtoupper(\Illuminate\Support\Str::random(8)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique('bookings', 'booking_code', ignoreRecord: true),
                TextInput::make('pax_count')
                    ->required()
                    ->numeric(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->default('pending')
                    ->required(),
                Textarea::make('special_requests')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
