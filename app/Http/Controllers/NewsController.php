<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Package;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $featured = News::where('is_published', true)->latest('published_at')->first();
        $posts = News::where('is_published', true)
            ->where('id', '!=', $featured?->id)
            ->latest('published_at')
            ->paginate(9);

        return view('news', compact('featured', 'posts'));
    }

    public function show(string $slug): View
    {
        $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $next = News::where('is_published', true)
            ->where('id', '!=', $news->id)
            ->inRandomOrder()
            ->first();

        $sidebarPackage = Package::with(['media', 'reviews'])
            ->inRandomOrder()
            ->first();

        return view('news_show', compact('news', 'next', 'sidebarPackage'));
    }
}
