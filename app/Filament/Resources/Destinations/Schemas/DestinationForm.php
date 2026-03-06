<?php

namespace App\Filament\Resources\Destinations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->unique('destinations', 'slug', ignoreRecord: true),
                \Filament\Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->disk('cloudinary')
                    ->directory('destinations')
                    ->afterStateHydrated(function (\Filament\Forms\Components\FileUpload $component, $record) {
                        if ($record && $media = $record->getFirstMedia('primary_image')) {
                            $component->state($media->public_id);
                        }
                    })
                    ->columnSpanFull(),
                \Filament\Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
