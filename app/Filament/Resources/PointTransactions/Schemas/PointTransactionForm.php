<?php

namespace App\Filament\Resources\PointTransactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PointTransactionForm
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
                Select::make('booking_id')
                    ->relationship('booking', 'booking_code')
                    ->searchable()
                    ->preload()
                    ->placeholder('No booking linked'),
                Select::make('reward_item_id')
                    ->relationship('rewardItem', 'title')
                    ->searchable()
                    ->preload()
                    ->placeholder('No reward linked'),
                Select::make('type')
                    ->options([
                        'earned' => 'Earned',
                        'redeemed' => 'Redeemed',
                        'bonus' => 'Bonus',
                    ])
                    ->required()
                    ->default('bonus'),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Bonus: Welcome Points'),
                TextInput::make('points')
                    ->required()
                    ->numeric()
                    ->helperText('Use positive for earned/bonus, negative for redeemed'),
            ]);
    }
}
