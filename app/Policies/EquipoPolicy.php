<?php

namespace App\Policies;

use App\Equipo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Sentinel;

class EquipoPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isOnGroup('programador') or $user->isOnGroup('administrador') or $user->isSupervisor() or $user->isOnGroup('tecnico')) {
            return true;
        }
    }

    public function see(User $user,Equipo $equipo){
        
        $clientes=explode(',',$user->crm_clientes_id);
        if($_SERVER['REMOTE_ADDR'] =='200.12.208.98')
            dd($clientes);
        if($user->isCliente() && in_array($equipo->cliente_id ,$clientes))
            return true;
        if(Sentinel::getUser()->hasAnyAccess(['equipos.detail']) && $user->tipo='gmp')
            return true;
        return false;
    }

}
