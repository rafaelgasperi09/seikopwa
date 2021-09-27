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
        if ($user->isOnGroup('Programador') or $user->isOnGroup('Administrador') or $user->isOnGroup('Supervisor') or $user->isOnGroup('Tecnico')) {
            return true;
        }
    }

    public function see(User $user,Equipo $equipo){
        $clientes=explode(',',$user->crm_clientes_id);
        if($user->isCliente() && in_array($equipo->cliente_id ,$clientes))
            return true;

        return false;
    }

}
