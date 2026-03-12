<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = \Illuminate\Support\Facades\Cache::remember('home_categories', 3600, function () {
            return Category::orderBy('name')->get();
        });
        
        // Get season from request, default to Spring
        $currentSeason = $request->get('season', 'Spring');
        
        // Validate season
        $validSeasons = array_keys(config('seasons', []));
        if (!in_array($currentSeason, $validSeasons)) {
            $currentSeason = $validSeasons[0] ?? 'Spring';
        }

        $seasonalTours = \Illuminate\Support\Facades\Cache::remember("home_seasonal_{$currentSeason}", 3600, function () use ($currentSeason) {
            return \App\Models\Package::with(['media', 'images'])
                ->where('season', $currentSeason)
                ->limit(3)
                ->get();
        });

        $seasonData = config("seasons.{$currentSeason}", config('seasons.Spring'));
        $seasonData['name'] = $currentSeason;
        $seasonData['year'] = now()->year;
        
        // Handle AJAX request for seamless season switching
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'seasonalToursHtml' => view('partials.seasonal_cards', compact('seasonalTours', 'currentSeason'))->render(),
                'seasonData' => $seasonData,
                'plannedUrl' => route('planned.index', ['seasons' => [$currentSeason]])
            ]);
        }

        $trendingTours = \Illuminate\Support\Facades\Cache::remember('home_trending', 3600, function () {
            return \App\Models\Package::with(['media', 'images'])
                ->where('type', 'open')
                ->where(function ($q) {
                    $q->where('is_trending', true)
                      ->orWhereHas('reviews');
                })
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->orderByRaw('CASE WHEN is_trending = 1 THEN 0 ELSE 1 END')
                ->orderByDesc('reviews_avg_rating')
                ->limit(3)
                ->get()
                ->map(function ($package) {
                    $package->average_rating = (float) ($package->reviews_avg_rating ?? 0);
                    $package->review_count = (int) ($package->reviews_count ?? 0);
                    return $package;
                });
        });

        $experiences = \Illuminate\Support\Facades\Cache::remember('home_experiences', 3600, function () {
            return \App\Models\Package::with(['media', 'images', 'relatedCategories'])
                ->where('type', 'activity')
                ->limit(3)
                ->get();
        });

        return view('index', compact('categories', 'seasonalTours', 'seasonData', 'currentSeason', 'trendingTours', 'experiences'));
    }
}
