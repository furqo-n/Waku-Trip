<?php

namespace App\Filament\Resources\SiteIcons\Pages;

use App\Filament\Resources\SiteIcons\SiteIconResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteIcons extends ManageRecords
{
    protected static string $resource = SiteIconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
