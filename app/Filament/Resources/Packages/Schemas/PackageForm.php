<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('destination_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('location_text')
                    ->default(null),
                TextInput::make('duration_days')
                    ->required()
                    ->numeric(),
                TextInput::make('group_size')
                    ->default(null),
                TextInput::make('language')
                    ->default(null),
                Toggle::make('is_guided')
                    ->required(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('type')
                    ->options(['open' => 'Open', 'private' => 'Private', 'activity' => 'Activity'])
                    ->default('open')
                    ->required(),
                TextInput::make('season')
                    ->default(null),
                Toggle::make('is_trending')
                    ->required(),
            ]);
    }
}
