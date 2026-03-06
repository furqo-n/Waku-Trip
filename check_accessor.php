<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Itinerary;

$itinerary = Itinerary::whereHas('media')->first();
if ($itinerary) {
    echo "ID: " . $itinerary->id . PHP_EOL;
    echo "Raw image_path attribute: " . ($itinerary->getAttributes()['image_path'] ?? 'N/A') . PHP_EOL;
    echo "Accessor image_path: " . $itinerary->image_path . PHP_EOL;
} else {
    echo "No Itinerary with media found" . PHP_EOL;
}
