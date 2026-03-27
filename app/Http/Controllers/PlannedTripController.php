<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Category;
use Illuminate\Http\Request;

class PlannedTripController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all categories for the filter
        $categories = Category::orderBy('name')->get();
        
        // Start building the query
        $query = Package::with(['media', 'relatedCategories', 'destination']);
        
        // Filter by trip type (open or private)
        $tripTypes = $request->get('trip_types', ['open']); // Default to 'open' only
        if (!empty($tripTypes)) {
            $query->whereIn('type', $tripTypes);
        }
        
        // Filter by categories if provided
        $selectedCategoriesRaw = $request->get('categories', []);
        $selectedCategories = array_filter($selectedCategoriesRaw); // Remove empty strings
        if (!empty($selectedCategories)) {
            $query->whereHas('relatedCategories', function($q) use ($selectedCategories) {
                $q->whereIn('categories.id', $selectedCategories);
            });
        }
        
        // Filter by season
        $seasonsRaw = $request->get('seasons', []);
        $seasons = array_filter($seasonsRaw); // Remove empty strings
        if (!empty($seasons)) {
            $query->whereIn('season', $seasons);
        }
        
        // Filter by destination search
        if ($request->filled('destination')) {
            $destination = $request->get('destination');
            $query->where(function($q) use ($destination) {
                $q->where('title', 'LIKE', "%{$destination}%")
                  ->orWhere('location_text', 'LIKE', "%{$destination}%")
                  ->orWhere('description', 'LIKE', "%{$destination}%");
            });
        }
        
        // Filter by price range
        $minPrice = $request->get('min_price', 0);
        $maxPrice = $request->get('max_price', 10000);
        
        if ($minPrice > 0 || $maxPrice < 10000) {
            $query->whereBetween('base_price', [$minPrice, $maxPrice]);
        }
        
        
        $packages = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        
        $selectedTripTypes = $tripTypes;
        $selectedSeasons = $seasons;

        return view('planned_list', compact('packages', 'categories', 'selectedCategories', 'selectedTripTypes', 'selectedSeasons', 'minPrice', 'maxPrice'));
    }

    public function privateList(Request $request)
    {
        // Fetch Categories for filter pills
        $categories = Category::has('packages')->orderBy('name')->get();

        // Base Query: Private Packages and Activities
        $query = Package::with(['media', 'relatedCategories', 'destination'])
            ->whereIn('type', ['private', 'activity']);

        // Filter by Category
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $categoryId = $request->get('category');
            $query->whereHas('relatedCategories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Get Results
        $allPackages = $query->orderBy('created_at', 'desc')->get();

        // Separate Featured (First one) vs Rest
        $featuredPackage = $allPackages->first();
        $packages = $allPackages->skip(1);

        return view('private_list', compact('categories', 'featuredPackage', 'packages'));
    }
}
