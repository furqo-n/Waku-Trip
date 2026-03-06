<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(3)
                    ->placeholder('USD'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('US Dollar'),
                TextInput::make('symbol')
                    ->required()
                    ->maxLength(10)
                    ->placeholder('$'),
                TextInput::make('rate')
                    ->required()
                    ->numeric()
                    ->default(1.0)
                    ->step(0.0001)
                    ->helperText('Exchange rate relative to USD (1.0)'),
                TextInput::make('format')
                    ->required()
                    ->default('$%s')
                    ->helperText('Use %s for the amount (e.g. $%s or %s €)'),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
