<?php

namespace App\Filament\Resources\TripSchedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;

class TripScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Schedule Details')
                    ->schema([
                        Select::make('package_id')
                            ->relationship('package', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                if (!$state) return;
                                $package = \App\Models\Package::find($state);
                                if (!$package) return;
                                
                                $startDate = $get('start_date');
                                if ($startDate) {
                                    $days = (int) $package->duration_days;
                                    $end = \Carbon\Carbon::parse($startDate)->addDays($days > 0 ? $days - 1 : 0);
                                    $set('end_date', $end->format('Y-m-d'));
                                }
                            }),
                        SchemaComponents\Grid::make(2)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($get, $set, $state) {
                                        if (!$state) return;
                                        $packageId = $get('package_id');
                                        if (!$packageId) return;
                                        
                                        $package = \App\Models\Package::find($packageId);
                                        if (!$package) return;
                                        
                                        $days = (int) $package->duration_days;
                                        $end = \Carbon\Carbon::parse($state)->addDays($days > 0 ? $days - 1 : 0);
                                        $set('end_date', $end->format('Y-m-d'));
                                    }),
                                DatePicker::make('end_date')
                                    ->required(),
                            ]),
                        
                        SchemaComponents\Grid::make(3)
                            ->schema([
                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$'),
                                TextInput::make('quota')
                                    ->required()
                                    ->numeric()
                                    ->default(10),
                                TextInput::make('available_seats')
                                    ->required()
                                    ->numeric()
                                    ->default(10)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $seats = (int) $state;
                                        if ($seats <= 0) {
                                            $set('status', 'full');
                                        } elseif ($get('status') === 'full') {
                                            $set('status', 'available');
                                        }
                                    }),
                            ]),
                        
                        Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'full' => 'Full',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ])
                            ->default('available')
                            ->required(),
                    ])
                    ->columnSpan(2),
                
                SchemaComponents\Section::make('Statistics')
                    ->description('Quick overview')
                    ->schema([
                        Placeholder::make('current_bookings')
                            ->label('Total Bookings')
                            ->content(fn ($record) => $record?->bookings()->count() ?? 0),
                        Placeholder::make('total_passengers')
                            ->label('Total Passengers')
                            ->content(fn ($record) => $record?->passengers()->count() ?? 0),
                    ])
                    ->columnSpan(1),

                SchemaComponents\Section::make('Trip Passengers')
                    ->description('List of passengers booked for this trip.')
                    ->schema([
                        Repeater::make('trip_manifest')
                            ->label('Passengers')
                            ->schema([
                                Select::make('booking_id')
                                    ->label('Booking ID')
                                    ->options(function ($record) {
                                        // $record in a form refers to the parent TripSchedule
                                        if (!$record) return [];
                                        return \App\Models\Booking::where('trip_schedule_id', $record->id)
                                            ->pluck('booking_code', 'id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),
                                TextInput::make('name')
                                    ->label('Passenger Name')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('passport_number')
                                    ->label('Passport')
                                    ->columnSpan(1),
                                Select::make('gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other',
                                    ])
                                    ->columnSpan(1),
                                DatePicker::make('date_of_birth')
                                    ->label('DOB')
                                    ->columnSpan(1),
                            ])
                            ->columns(5)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->afterStateHydrated(function ($set, $record) {
                                if ($record) {
                                    $set('trip_manifest', $record->passengers->toArray());
                                }
                            })
                            ->saveRelationshipsUsing(function ($record, $state) {
                                // Manual saving for HasManyThrough simulation
                                $passengerIds = collect($state)->pluck('id')->filter()->toArray();
                                
                                // Optional: Delete passengers removed from the repeater
                                \App\Models\BookingPassenger::whereHas('booking', function($q) use ($record) {
                                    $q->where('trip_schedule_id', $record->id);
                                })->whereNotIn('id', $passengerIds)->delete();

                                foreach ($state as $data) {
                                    if (isset($data['id'])) {
                                        \App\Models\BookingPassenger::find($data['id'])->update($data);
                                    } else {
                                        \App\Models\BookingPassenger::create($data);
                                    }
                                }
                            }),
                    ])
                    ->collapsible()
                    ->columnSpan(3),
            ])
            ->columns(3);
    }
}
