<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Itinerary;
use App\Models\Package;

$package = Package::first();

// simulates filament save
$itinerary = new Itinerary([
    'package_id' => $package->id,
    'day_number' => 99,
    'title' => 'Test Day',
    'description' => 'Test Description',
    'upload_status' => 'uploaded'
]);
$rawAttributes = $itinerary->getAttributes();
$rawAttributes['image_path'] = 'itineraries/sample_new.jpg';
$itinerary->setRawAttributes($rawAttributes);
$itinerary->save();

echo "Itinerary saved! ID: " . $itinerary->id . PHP_EOL;
$media = $itinerary->getMedia('primary_image');
echo "Media count: " . $media->count() . PHP_EOL;

if ($media->count() > 0) {
    echo "Media ID: " . $media->first()->id . " URL: " . $media->first()->url . PHP_EOL;
    
    // Test update
    $rawAttributes = $itinerary->getAttributes();
    $rawAttributes['image_path'] = 'itineraries/sample_updated.jpg';
    $itinerary->setRawAttributes($rawAttributes);
    $itinerary->save();
    
    $itinerary->refresh();
    $media2 = $itinerary->getMedia('primary_image');
    echo "Media count after update: " . $media2->count() . PHP_EOL;
    if ($media2->count() > 0) {
        echo "Media ID: " . $media2->first()->id . " URL: " . $media2->first()->url . PHP_EOL;
    }
}
