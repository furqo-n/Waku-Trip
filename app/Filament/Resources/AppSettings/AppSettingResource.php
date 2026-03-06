<?php

namespace App\Filament\Resources\AppSettings;

use App\Filament\Resources\AppSettings\Pages\ManageAppSettings;
use App\Models\AppSetting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class AppSettingResource extends Resource
{
    protected static ?string $model = AppSetting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog';
    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    protected static ?string $modelLabel = 'Site Setting';

    protected static ?string $recordTitleAttribute = 'site_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->description('Global setting for the website.')
                    ->schema([
                        TextInput::make('site_name')
                            ->required()
                            ->maxLength(255),
                    ]),
                
                Section::make('Fallback Images')
                    ->description('These images will be used if the main image is missing.')
                    ->schema([
                        FileUpload::make('default_tour_image')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('default_tour_image')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['default_tour_image'] = $state;
                            }),
                        
                        FileUpload::make('default_news_image')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('default_news_image')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['default_news_image'] = $state;
                            }),
                            
                        FileUpload::make('default_avatar')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('default_avatar')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['default_avatar'] = $state;
                            }),
                    ])->columns(3),

                Section::make('Page Backgrounds')
                    ->description('Background images for static pages.')
                    ->schema([
                        FileUpload::make('login_bg_1')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('login_bg_1')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['login_bg_1'] = $state;
                            }),
                        FileUpload::make('login_bg_2')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('login_bg_2')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['login_bg_2'] = $state;
                            }),
                        FileUpload::make('login_bg_3')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('login_bg_3')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['login_bg_3'] = $state;
                            }),
                        FileUpload::make('register_bg')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('register_bg')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['register_bg'] = $state;
                            }),
                    ]),
                \Filament\Schemas\Components\Section::make('Home Page')
                    ->description('Settings for the landing page')
                    ->schema([
                        FileUpload::make('home_hero_bg')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('settings')
                            ->afterStateHydrated(function (FileUpload $component, $record) {
                                if ($record && $media = $record->getFirstMedia('home_hero_bg')) {
                                    $component->state($media->public_id);
                                }
                            })
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->pendingMediaUploads['home_hero_bg'] = $state;
                            }),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('site_name')
            ->columns([
                TextColumn::make('site_name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAppSettings::route('/'),
        ];
    }
}
