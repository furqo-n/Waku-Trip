<?php

namespace App\Filament\Resources\Packages\Pages;

use App\Filament\Resources\Packages\PackageResource;
use App\Jobs\UploadImageToCloudinary;
use App\Models\Itinerary;
use App\Models\PackageImage;
use Filament\Resources\Pages\CreateRecord;

class CreatePackage extends CreateRecord
{
    protected static string $resource = PackageResource::class;

    protected function afterCreate(): void
    {
        $package = $this->record;

        // Dispatch upload jobs for each package image
        foreach ($package->images as $image) {
            if ($image->image_path && !str_starts_with($image->image_path, 'http')) {
                $image->update(['upload_status' => 'pending']);
                UploadImageToCloudinary::dispatch(
                    PackageImage::class,
                    $image->id,
                    'image_path',
                    'packages',
                );
            }
        }

        // Dispatch upload jobs for each itinerary image
        foreach ($package->itineraries as $itinerary) {
            if ($itinerary->image_path && !str_starts_with($itinerary->image_path, 'http')) {
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
