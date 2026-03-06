<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cloudinary unlinked image garbage collector
Artisan::command('media:cleanup-orphans', function () {
    $this->call('media:cleanup-orphans');
})->purpose('Deletes unassigned images from Cloudinary')->daily();
