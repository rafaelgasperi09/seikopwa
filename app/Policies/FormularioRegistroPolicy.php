<?php

namespace App\Policies;

use App\FormularioRegistro;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormularioRegistroPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isOnGroup('Programador')) {
            return true;
        }
    }

    public function edit(User $user,FormularioRegistro $formularoRegistro){

        if($formularoRegistro->estatus <> 'C' 
            && ($user->isOnGroup('SupervisorC') or $user->isOnGroup('Supervisor')))
            return true;

        return false;
    }
}
