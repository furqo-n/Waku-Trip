<?php

namespace App\Filament\Resources\RewardItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RewardItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->disk('cloudinary')
                    ->directory('rewards')
                    ->afterStateHydrated(function (FileUpload $component, $record) {
                        if ($record && $media = $record->getFirstMedia('reward_image')) {
                            $component->state($media->public_id);
                        }
                    })
                    ->columnSpanFull(),
                TextInput::make('points_cost')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix('pts'),
                Select::make('badge')
                    ->options([
                        'Limited Time' => 'Limited Time',
                        'Exclusive' => 'Exclusive',
                        'New' => 'New',
                    ])
                    ->placeholder('No badge'),
                Select::make('badge_class')
                    ->options([
                        'limited' => 'Dark (Limited)',
                        'exclusive' => 'Red (Exclusive)',
                        'new' => 'Green (New)',
                    ])
                    ->placeholder('No style'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
