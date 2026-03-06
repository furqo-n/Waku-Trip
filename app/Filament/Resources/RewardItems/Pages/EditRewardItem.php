<?php

namespace App\Filament\Resources\RewardItems\Pages;

use App\Filament\Resources\RewardItems\RewardItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRewardItem extends EditRecord
{
    protected static string $resource = RewardItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
