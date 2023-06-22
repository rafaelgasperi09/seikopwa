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
        if ($user->isOnGroup('programador') or $user->isOnGroup('administrador') ) {
            return true;
        }
    }

    public function edit(User $user,FormularioRegistro $formularoRegistro){

        if($formularoRegistro->estatus <> 'C' 
            && ($user->isOnGroup('supervisorc') or $user->isSupervisor()))
            return true;

        return false;
    }
}
