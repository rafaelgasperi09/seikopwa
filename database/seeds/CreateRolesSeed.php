<?php

use Illuminate\Database\Seeder;

class CreateRolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos_admin = array();
        $permisos_user = array();
        foreach(config('permisos.permissions') as $key => $permisos){
            foreach($permisos as $key2 => $value){
                $exp = explode('.',$key2);
                if($key=='Roles' || $exp[1]=='destroy')
                {
                    $permisos_admin[$key2]=true;
                }else{
                    $permisos_admin[$key2]=true;
                    $permisos_user[$key2]=true;
                }

            }
        }

        $adminRole = [
            'name' => 'Programador',
            'slug' => 'programador',
            'tipo'=>'all',
            'permissions' => $permisos_admin
        ];

        Sentinel::getRoleRepository()->createModel()->fill($adminRole)->save();

        $userRole = [
            'name' => 'Administrador',
            'slug' => 'administrador',
            'tipo'=>'gmp',
            'permissions' => $permisos_user
        ];

        Sentinel::getRoleRepository()->createModel()->fill($userRole)->save();

        $clienteRole = [
            'name' => 'OperadorC',
            'slug' => 'supervisor-c',
            'tipo'=>'cliente',
            'permissions' => $permisos_user
        ];

        Sentinel::getRoleRepository()->createModel()->fill($clienteRole)->save();

        $clienteRole = [
            'name' => 'SupervisorC',
            'slug' => 'operador-c',
            'tipo'=>'cliente',
            'permissions' => $permisos_user
        ];

        Sentinel::getRoleRepository()->createModel()->fill($clienteRole)->save();

        $clienteRole = [
            'name' => 'Supervisor',
            'slug' => 'supervisor',
            'tipo'=>'gmp',
            'permissions' => $permisos_user
        ];

        Sentinel::getRoleRepository()->createModel()->fill($clienteRole)->save();

        $clienteRole = [
            'name' => 'Tecnico',
            'slug' => 'tecnico',
            'tipo'=>'gmp',
            'permissions' => $permisos_user
        ];

        Sentinel::getRoleRepository()->createModel()->fill($clienteRole)->save();
    }
}
