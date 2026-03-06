<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function aboutUs(): View
    {
        return view('aboutus');
    }

    public function custome(): View
    {
        return view('custome');
    }

    public function japanMap(): View
    {
        return view('japan-map-interactive');
    }
}
