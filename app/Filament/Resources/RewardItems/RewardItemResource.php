<?php

namespace App\Filament\Resources\RewardItems;

use App\Filament\Resources\RewardItems\Pages\CreateRewardItem;
use App\Filament\Resources\RewardItems\Pages\EditRewardItem;
use App\Filament\Resources\RewardItems\Pages\ListRewardItems;
use App\Filament\Resources\RewardItems\Schemas\RewardItemForm;
use App\Filament\Resources\RewardItems\Tables\RewardItemsTable;
use App\Models\RewardItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RewardItemResource extends Resource
{
    protected static ?string $model = RewardItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string | \UnitEnum | null $navigationGroup = 'Rewards';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RewardItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RewardItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRewardItems::route('/'),
            'create' => CreateRewardItem::route('/create'),
            'edit' => EditRewardItem::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}
