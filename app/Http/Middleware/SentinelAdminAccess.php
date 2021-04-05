<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class SentinelAdminAccess
{
    public function handle($request, Closure $next)
    {
        if(!Sentinel::check())
        {
            $request->session()->flash('message.error', 'Su usuario no tiene acceso para ver esta sección.');
            return response()->view('login', [], 403);
        }elseif(!Sentinel::inRole('administradores')){
            $request->session()->flash('message.error', 'Su usuario no tiene acceso para ver esta sección.');
            return response()->view('login', [], 403);
        }

        return $next($request);
    }
}