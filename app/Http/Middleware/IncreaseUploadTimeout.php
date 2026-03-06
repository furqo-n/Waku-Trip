<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncreaseUploadTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        set_time_limit(300); // 5 minutes for Cloudinary uploads

        return $next($request);
    }
}
