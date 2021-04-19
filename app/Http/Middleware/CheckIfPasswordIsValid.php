<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Sentinel;
use \App\User;

class CheckIfPasswordIsValid
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
        $data = User::find(current_user()->id);
        if($data->have_to_change_password)
        {
            return redirect(route('usuarios.update_password_view',current_user()->id));
        }
        else
        {
            $currdate = date('Y-m-d');
            $today = Carbon::parse($currdate);
            $fechaLastUpdatePassword = Carbon::parse($data->date_last_password_changed);
            $diasDiferencia = $today->diffInDays($fechaLastUpdatePassword);

        }


        return $next($request);

    }
}
