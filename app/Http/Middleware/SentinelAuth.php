<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class SentinelAuth
{
    public function handle($request, Closure $next)
    {
        if(!Sentinel::check())
        {
            $request->session()->flash('message.error', 'Para ver esta sección debe ingresar con su usuario y contraseña.');
            return response()->view('login', [], 403);
        }

        return $next($request);
    }
}