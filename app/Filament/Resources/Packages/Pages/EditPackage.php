<?php

namespace App\Filament\Resources\Packages\Pages;

use App\Filament\Resources\Packages\PackageResource;
use App\Jobs\UploadImageToCloudinary;
use App\Models\Itinerary;
use App\Models\PackageImage;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPackage extends EditRecord
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $package = $this->record;

        // Dispatch upload jobs for any new/changed package images still on local disk
        foreach ($package->images as $image) {
            if (
                $image->image_path &&
                !str_starts_with($image->image_path, 'http') &&
                $image->upload_status !== 'pending'
            ) {
                $image->update(['upload_status' => 'pending']);
                UploadImageToCloudinary::dispatch(
                    PackageImage::class,
                    $image->id,
                    'image_path',
                    'packages',
                );
            }
        }

        // Dispatch upload jobs for any new/changed itinerary images
        foreach ($package->itineraries as $itinerary) {
            if (
                $itinerary->image_path &&
                !str_starts_with($itinerary->image_path, 'http') &&
                $itinerary->upload_status !== 'pending'
            ) {
                $itinerary->update(['upload_status' => 'pending']);
                UploadImageToCloudinary::dispatch(
                    Itinerary::class,
                    $itinerary->id,
                    'image_path',
                    'itineraries',
                );
            }
        }
    }
}
