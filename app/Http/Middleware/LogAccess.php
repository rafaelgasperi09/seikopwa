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

            $rawAgent = $request->userAgent();
            $userAgent = UserAgent::firstOrCreate(['agent' => $rawAgent]);

            AccessLog::create([
                'user_id'        => $auth->id,
                'user_agent_id'  => $userAgent->id,
                'ip_address'     => $request->header('CF-Connecting-IP', $request->ip()),
                'url'            => $request->fullUrl(),
            ]);
        }
        */

        return $next($request);
    }
}
