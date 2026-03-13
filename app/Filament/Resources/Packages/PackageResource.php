<?php

namespace App\Filament\Resources\Packages;

use App\Filament\Resources\Packages\Pages;
use App\Models\Package;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';

    protected static string | \UnitEnum | null $navigationGroup = 'Tours Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Group::make()
                    ->schema([
                        SchemaComponents\Section::make('Package Details')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Package::class, 'slug', ignoreRecord: true),

                                Forms\Components\RichEditor::make('description')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('location_text')
                                    ->label('Location')
                                    ->placeholder('e.g., Tokyo • Kyoto • Osaka')
                                    ->maxLength(255),

                                SchemaComponents\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('base_price')
                                            ->label('Starting Price ($)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required(),

                                        Forms\Components\TextInput::make('duration_days')
                                            ->label('Duration (Days)')
                                            ->numeric()
                                            ->required(),

                                        Forms\Components\Select::make('season')
                                            ->options([
                                                'Spring' => 'Spring',
                                                'Summer' => 'Summer',
                                                'Autumn' => 'Autumn',
                                                'Winter' => 'Winter',
                                            ])
                                            ->native(false),

                                        Forms\Components\TextInput::make('group_size')
                                            ->label('Group Size')
                                            ->placeholder('e.g. Max 10')
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('language')
                                            ->label('Language')
                                            ->placeholder('e.g. English, Japanese')
                                            ->maxLength(255),

                                        Forms\Components\Toggle::make('is_guided')
                                            ->label('Guided Tour')
                                            ->default(true)
                                            ->inline(false),
                                    ]),
                                
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'open' => 'Open Trip',
                                        'private' => 'Private Trip',
                                        'activity' => 'Activity',
                                    ])
                                    ->default('open')
                                    ->native(false)
                                    ->required(),

                                Forms\Components\Toggle::make('is_trending')
                                    ->label('Trending')
                                    ->default(false),
                            ]),
                        SchemaComponents\Section::make('Categories')
                            ->schema([
                                Forms\Components\Select::make('relatedCategories')
                                    ->label('Categories')
                                    ->relationship('relatedCategories', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->placeholder('Select categories...'),
                            ]),
                            
                        SchemaComponents\Section::make('Inclusions')
                            ->schema([
                                Forms\Components\Repeater::make('inclusions')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('item')->required(),
                                        Forms\Components\Toggle::make('is_included')->default(true)->label('Included?'),
                                    ])
                                    ->defaultItems(1)
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                SchemaComponents\Group::make()
                    ->schema([
                        SchemaComponents\Section::make('Media')
                            ->schema([
                                Forms\Components\FileUpload::make('primary_image')
                                    ->image()
                                    ->disk('cloudinary')
                                    ->directory('packages')
                                    ->maxSize(8192) // 8MB
                                    ->imageResizeTargetWidth(1920)
                                    ->imageResizeTargetHeight(1080)
                                    ->imageResizeMode('cover')
                                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $record) {
                                        if ($record && $media = $record->getFirstMedia('primary_image')) {
                                            $component->state($media->public_id);
                                        }
                                    })
                                    ->dehydrated(false)
                                    ->saveRelationshipsUsing(function ($record, $state) {
                                        if (empty($state)) {
                                            $record->media()->wherePivot('collection_name', 'primary_image')->detach();
                                            return;
                                        }

                                        $cloudName = config('filesystems.disks.cloudinary.cloud');
                                        
                                        // Always detach existing primary image
                                        $record->media()->wherePivot('collection_name', 'primary_image')->detach();

                                        $media = \App\Models\MediaAsset::firstOrCreate(
                                            ['public_id' => $state],
                                            [
                                                'url' => "https://res.cloudinary.com/{$cloudName}/image/upload/{$state}",
                                                'status' => 'permanent'
                                            ]
                                        );

                                        $record->media()->attach($media->id, ['collection_name' => 'primary_image']);
                                    }),

                                Forms\Components\FileUpload::make('gallery_images')
                                    ->multiple()
                                    ->image()
                                    ->disk('cloudinary')
                                    ->directory('packages')
                                    ->maxSize(8192)
                                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $record) {
                                        if ($record) {
                                            $urls = $record->getMedia('gallery')->pluck('public_id', 'url')->toArray();
                                            $component->state($urls);
                                        }
                                    })
                                    ->dehydrated(false)
                                    ->saveRelationshipsUsing(function ($record, $state) {
                                        // 1. Detach all current gallery media for this package to handle removals easily
                                        $record->media()->wherePivot('collection_name', 'gallery')->detach();

                                        if (empty($state)) {
                                            return;
                                        }

                                        $cloudName = config('filesystems.disks.cloudinary.cloud');
                                        $syncData = [];

                                        foreach ($state as $path) {
                                            if (empty($path)) continue;

                                            // If it's already a full URL
                                            if (filter_var($path, FILTER_VALIDATE_URL)) {
                                                 $media = \App\Models\MediaAsset::where('url', $path)->first();
                                                 if ($media) {
                                                     $syncData[$media->id] = ['collection_name' => 'gallery'];
                                                 }
                                                 continue;
                                            }

                                            $media = \App\Models\MediaAsset::firstOrCreate(
                                                ['public_id' => $path],
                                                [
                                                    'url' => "https://res.cloudinary.com/{$cloudName}/image/upload/{$path}",
                                                    'status' => 'permanent'
                                                ]
                                            );
                                            $syncData[$media->id] = ['collection_name' => 'gallery'];
                                        }

                                        if (!empty($syncData)) {
                                            $record->media()->syncWithoutDetaching($syncData);
                                        }
                                    })
                            ]),

                        SchemaComponents\Section::make('Itinerary')
                            ->schema([
                                Forms\Components\Repeater::make('itineraries')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('day_number')->numeric()->required(),
                                        Forms\Components\TextInput::make('title')->required(),
                                        Forms\Components\Textarea::make('description')->rows(3)->required(),
                                        Forms\Components\FileUpload::make('image_path')
                                            ->image()
                                            ->disk('cloudinary')
                                            ->directory('itineraries')
                                            ->maxSize(8192)
                                            ->imageResizeTargetWidth(1920)
                                            ->imageResizeTargetHeight(1080)
                                            ->imageResizeMode('cover')
                                            ->afterStateHydrated(function (Forms\Components\FileUpload $component, $record) {
                                                if ($record && $media = $record->getFirstMedia('primary_image')) {
                                                    $component->state($media->public_id);
                                                }
                                            }),
                                    ])
                                    ->orderColumn('day_number')
                                    ->defaultItems(1)
                                    ->collapsed(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'private' => 'warning',
                        'activity' => 'info',
                    }),
                Tables\Columns\TextColumn::make('season')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('group_size')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('language')->searchable()->toggleable(),
                Tables\Columns\IconColumn::make('is_guided')->boolean()->label('Guided')->toggleable(),
                Tables\Columns\IconColumn::make('is_trending')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => $state?->format('d M Y, H:i'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type'),
                Tables\Filters\SelectFilter::make('season'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\TripSchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
