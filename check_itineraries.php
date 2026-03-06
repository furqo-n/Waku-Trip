<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Itinerary;
use App\Models\MediaAsset;
use Illuminate\Support\Facades\DB;

$itineraries = Itinerary::latest('updated_at')->take(5)->get();
foreach ($itineraries as $itinerary) {
    echo "Itinerary ID: " . $itinerary->id . " - Title: " . $itinerary->title . PHP_EOL;
    echo "Attributes array: " . json_encode($itinerary->getAttributes()) . PHP_EOL;
    
    $media = $itinerary->getMedia('primary_image');
    echo "Media count: " . $media->count() . PHP_EOL;
    foreach ($media as $m) {
        echo "  - Media ID: " . $m->id . ", URL: " . $m->url . ", Public ID: " . $m->public_id . PHP_EOL;
    }
    
    $mediables = DB::table('mediables')->where('mediable_type', Itinerary::class)->where('mediable_id', $itinerary->id)->get();
    echo "Mediables count: " . $mediables->count() . PHP_EOL;
    foreach ($mediables as $m) {
        echo "  - Mediable: media_asset_id=" . $m->media_asset_id . " collection=" . $m->collection_name . PHP_EOL;
    }
    echo "--------------------------" . PHP_EOL;
}
