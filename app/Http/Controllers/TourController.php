<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function show($slug)
    {
        // specific tour by slug
        $package = Package::where('slug', $slug)
            ->with([
                'media', 
                'itineraries', 
                'inclusions', 
                'tripSchedules' => function($query) {
                    $query->where('start_date', '>=', now())
                          ->where('status', 'available')
                          ->orderBy('start_date');
                },
                'reviews.user' // Eager load reviews and their authors
            ])
            ->firstOrFail();

        // Calculate average rating if needed (though you might want to cache this or use a package)
        $averageRating = $package->reviews()->avg('rating') ?? 0;
        $reviewCount = $package->reviews()->count();

        return view('tour_detail', compact('package', 'averageRating', 'reviewCount'));
    }
}
