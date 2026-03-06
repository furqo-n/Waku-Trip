<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function switch(Request $request)
    {
        $request->validate([
            'currency' => 'required|in:USD,IDR,EUR,JPY',
        ]);

        $this->currencyService->setCurrency($request->currency);

        return back()->with('success', 'Currency changed to ' . $request->currency);
    }
}
