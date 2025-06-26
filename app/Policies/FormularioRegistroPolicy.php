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
        $clientes=explode(',',current_user()->crm_clientes_id);
        //if(in_array($formularoRegistro->cliente_id,$clientes) or !current_user()->isCliente()){
            if($formularoRegistro->estatus <> 'C' && ( $user->isSupervisor() ) ){
                return true;
            }
        //}

        return false;
    }
}
