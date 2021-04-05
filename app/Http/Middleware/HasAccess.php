<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class HasAccess
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
        if(Sentinel::check())
        {
            if(!Sentinel::getUser()->hasAccess(\Route::currentRouteName()))
            {
                //$request->session()->flash('message.error', 'Su usuario no tiene acceso para ver esta seccion.');
               // return view('front.auth.login');
                return response()->view('noaccess', [], 403);
            }
        }


        return $next($request);

    }
}
