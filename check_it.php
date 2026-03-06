<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Itinerary;

$itineraries = Itinerary::orderBy('id', 'desc')->take(3)->get();
foreach ($itineraries as $itinerary) {
    echo "Itinerary ID: " . $itinerary->id . " - Title: " . $itinerary->title . PHP_EOL;
    echo "Attributes array: " . json_encode($itinerary->getAttributes()) . PHP_EOL;
    
    $media = $itinerary->getMedia('primary_image');
    echo "Media count: " . $media->count() . PHP_EOL;
    foreach ($media as $m) {
        echo "  - Media ID: " . $m->id . ", URL: " . $m->url . ", Public ID: " . $m->public_id . PHP_EOL;
    }
    echo "--------------------------" . PHP_EOL;
}
