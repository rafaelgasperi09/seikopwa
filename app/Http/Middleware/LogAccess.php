<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\AccessLog;
use Sentinel;

class LogAccess
{
    public function handle(Request $request, Closure $next)
    {
        /*
        if ($user = Sentinel::getUser()) {
            AccessLog::create([
                'user_id'    => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url'        => $request->fullUrl(),
            ]);
        }
        */

        return $next($request);
    }
}
