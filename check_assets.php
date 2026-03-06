<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MediaAsset;

$latest = MediaAsset::latest()->first();
if ($latest) {
    echo "ID: " . $latest->id . PHP_EOL;
    echo "Public ID: " . $latest->public_id . PHP_EOL;
    echo "URL: " . $latest->url . PHP_EOL;
} else {
    echo "No MediaAsset found" . PHP_EOL;
}
