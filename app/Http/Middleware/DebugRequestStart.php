<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DebugRequestStart
{
    public function handle(Request $request, Closure $next)
    {
        // #region agent log
        $t = (int)(microtime(true) * 1000);
        file_put_contents(base_path('debug-4883c6.log'), json_encode(['sessionId'=>'4883c6','runId'=>'run1','hypothesisId'=>'H5','location'=>'DebugRequestStart.php:14','message'=>'request start (before session)','data'=>['ts_ms'=>$t,'path'=>$request->path()],'timestamp'=>$t])."\n", FILE_APPEND);
        // #endregion
        return $next($request);
    }
}
