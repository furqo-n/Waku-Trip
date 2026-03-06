<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

try {
    $path = 'itineraries/sample.jpg';
    $url = Storage::disk('cloudinary')->url($path);
    echo "Storage URL: " . $url . PHP_EOL;
    
    // Check if we can get it via the underlying Cloudinary object manually
    if (class_exists('CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary')) {
         echo "Cloudinary Facade exists" . PHP_EOL;
         // Some versions use this
         try {
             $cUrl = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::getUrl($path);
             echo "Facade getUrl: " . $cUrl . PHP_EOL;
         } catch (\Throwable $e) {
             echo "Facade getUrl failed: " . $e->getMessage() . PHP_EOL;
         }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
