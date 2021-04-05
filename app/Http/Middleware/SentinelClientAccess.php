<?php

namespace App\Http\Middleware;

use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class SentinelClientAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Sentinel::check())
        {
            $request->session()->flash('message.error', 'Su usuario no tiene acceso para ver esta sección.');
            return response()->view('login', [], 403);
        }elseif(!Sentinel::inRole('samsung')){
            $request->session()->flash('message.error', 'Su usuario no tiene acceso para ver esta sección.');
            return response()->view('login', [], 403);
        }

        return $next($request);
    }
}
