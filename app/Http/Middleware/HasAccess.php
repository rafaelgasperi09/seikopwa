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

            if(!( Sentinel::getUser()->hasAccess(\Route::currentRouteName()) or Sentinel::getUser()->isOnGroup(1)) )
            {
                //$request->session()->flash('message.error', 'Su usuario no tiene acceso para ver esta seccion.');
               // return view('front.auth.login');
                return response()->view('frontend.noaccess', [], 403);
            }
        }

        return $next($request);

    }
}
