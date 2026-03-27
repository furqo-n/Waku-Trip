<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use App\Models\Voucher;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(50)
                    ->unique(Voucher::class, 'code', ignoreRecord: true)
                    ->regex('/^[A-Z0-9_-]+$/')
                    ->helperText('Uppercase letters, numbers, underscores only'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(1000)
                    ->rows(3),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('max_discount')
                    ->numeric()
                    ->minValue(0)
                    ->label('Max Discount Amount'),
                TextInput::make('min_order_amount')
                    ->numeric()
                    ->minValue(0)
                    ->label('Minimum Order Amount'),
                TextInput::make('usage_limit')
                    ->numeric()
                    ->minValue(1)
                    ->label('Total Usage Limit'),
                TextInput::make('usage_limit_per_user')
                    ->numeric()
                    ->minValue(1)
                    ->label('Usage Limit Per User'),
                DateTimePicker::make('starts_at')
                    ->required()
                    ->label('Start Date')
                    ->default(now()),
                DateTimePicker::make('expires_at')
                    ->required()
                    ->label('Expiry Date')
                    ->after('starts_at'),
                Select::make('type')
                    ->required()
                    ->options([
                        'percentage' => 'Percentage Discount',
                        'fixed_amount' => 'Fixed Amount',
                    ]),
                Select::make('target_type')
                    ->required()
                    ->options([
                        'all' => 'All Products',
                        'products' => 'Specific Products',
                        'categories' => 'Specific Categories',
                        'user_groups' => 'User Groups',
                    ]),
                Select::make('target_user_group')
                    ->label('User Group')
                    ->options(fn() => \App\Models\VoucherUserGroup::pluck('name', 'name')->toArray())
                    ->searchable()
                    ->default(null),
                TextInput::make('min_user_account_age_days')
                    ->numeric()
                    ->minValue(0)
                    ->default(null)
                    ->label('Minimum Account Age (days)'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Toggle::make('is_stackable')
                    ->label('Stackable')
                    ->default(false),
                TextInput::make('max_stackable')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->label('Max Stackable Vouchers'),
            ]);
    }
}
