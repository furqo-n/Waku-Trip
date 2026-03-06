<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $state) {
                        $set('slug', Str::slug($state));
                    }),
                    
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                    
                TextInput::make('author')
                    ->default('Waku Trip Team')
                    ->maxLength(255),
                    
                FileUpload::make('image_path')
                    ->image()
                    ->disk('cloudinary')
                    ->directory('news')
                    ->afterStateHydrated(function (FileUpload $component, $record) {
                        if ($record && $media = $record->getFirstMedia('primary_image')) {
                            $component->state($media->public_id);
                        }
                    })
                    ->columnSpanFull(),
                    
                Textarea::make('excerpt')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
                    
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                    
                DatePicker::make('published_at')
                    ->native(false),
                    
                Toggle::make('is_published')
                    ->default(true),
            ]);
    }
}
