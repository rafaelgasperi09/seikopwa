<?php

namespace App\Policies;

use App\Equipo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipoPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isOnGroup('Administradores')) {
            return true;
        }
    }

    public function see(User $user,Equipo $equipo){

        if($user->isCliente() && $equipo->cliente_id == $user->crm_cliente_id)
            return true;

        return false;
    }

}
