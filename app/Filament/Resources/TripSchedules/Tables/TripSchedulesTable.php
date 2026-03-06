<?php

namespace App\Filament\Resources\TripSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TripSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
             ->columns([
                TextColumn::make('package.title')
                    ->label('Package')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->formatStateUsing(fn ($state) => $state?->format('d M Y'))
                    ->sortable(),
                TextColumn::make('end_date')
                    ->formatStateUsing(fn ($state) => $state?->format('d M Y'))
                    ->sortable(),
                TextColumn::make('price')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2))
                    ->sortable(),
                TextColumn::make('quota')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('available_seats')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'full' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                    }),
                TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => $state?->format('d M Y, H:i'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->formatStateUsing(fn ($state) => $state?->format('d M Y, H:i'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
