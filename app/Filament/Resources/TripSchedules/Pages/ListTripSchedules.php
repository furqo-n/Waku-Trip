<?php

namespace App\Filament\Resources\TripSchedules\Pages;

use App\Filament\Resources\TripSchedules\TripScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTripSchedules extends ListRecords
{
    protected static string $resource = TripScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
