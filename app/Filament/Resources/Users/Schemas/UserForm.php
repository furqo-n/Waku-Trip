<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique('users', 'email', ignoreRecord: true),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ])
                    ->default('user')
                    ->required(),
                \Filament\Forms\Components\FileUpload::make('avatar')
                    ->avatar()
                    ->disk('cloudinary')
                    ->directory('avatars')
                    ->afterStateHydrated(function (\Filament\Forms\Components\FileUpload $component, $record) {
                        if ($record && $media = $record->getFirstMedia('avatar')) {
                            $component->state($media->public_id);
                        }
                    })
                    ->columnSpanFull(),
            ]);
    }
}
