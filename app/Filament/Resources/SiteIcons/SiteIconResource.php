<?php

namespace App\Filament\Resources\SiteIcons;

use App\Filament\Resources\SiteIcons\Pages\ManageSiteIcons;
use App\Models\SiteIcon;
use BackedEnum;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class SiteIconResource extends Resource
{
    protected static ?string $model = SiteIcon::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-code-bracket';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site Icons (SVG)';

    protected static ?string $pluralModelLabel = 'Site Icons';

    public static function form(Schema $form): Schema
    {
        return $form->components([
            TextInput::make('name')
                ->label('Icon Name')
                ->placeholder('e.g. Facebook Icon')
                ->required()
                ->maxLength(255)
                ->columnSpan(1),

            TextInput::make('key')
                ->label('Icon Key')
                ->placeholder('e.g. facebook_icon')
                ->required()
                ->unique(SiteIcon::class, 'key', ignoreRecord: true)
                ->maxLength(255)
                ->helperText('Use snake_case. This is what you call in Blade with site_icon("key")')
                ->columnSpan(1),

            Textarea::make('svg_code')
                ->label('SVG Code')
                ->placeholder('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">...</svg>')
                ->required()
                ->rows(12)
                ->helperText('Paste the raw inline SVG markup here. Tip: add width/height attributes or use CSS classes for sizing.')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('svg_code')
                    ->label('SVG Preview')
                    ->limit(60)
                    ->placeholder('—'),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteIcons::route('/'),
        ];
    }
}
