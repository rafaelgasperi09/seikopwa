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
        if ($user->isOnGroup('programador') or $user->isOnGroup('administrador') or $user->isOnGroup('administrador-cliente') ) {
            return true;
        }
    }

    public function edit(User $user,FormularioRegistro $formularoRegistro){

        if($formularoRegistro->estatus <> 'C' 
            && ( $user->isSupervisor() ) )
            return true;

        return false;
    }
}
