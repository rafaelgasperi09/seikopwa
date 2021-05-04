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
        /*$permisos_admin = array();
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
        }*/

        $programRole = [
            'name' => 'Programador',
            'slug' => 'programador',
            'tipo'=>'all',
            'permissions' => json_decode('{"usuarios.index":true,"usuarios.create":true,"usuarios.import":true,"usuarios.store":true,"usuarios.detail":true,"usuarios.update":true,"usuarios.profile":true,"role.index":true,"role.create":true,"role.store":true,"role.show":true,"role.edit":true,"role.update":true,"equipos.index":true,"equipos.create":true,"equipos.create_daily_check":true,"equipos.edit_daily_check":true,"equipos.create_mant_prev":true,"equipos.edit_mant_prev":true,"equipos.create_tecnical_support":true,"equipos.detail":true,"baterias.index":true,"baterias.detail":true,"baterias.register_in_and_out":true,"parteA":true,"parteB":true,"parteC":true}')
        ];

        Sentinel::getRoleRepository()->createModel()->fill($programRole)->save();

        $adminRole = [
            'name' => 'Administrador',
            'slug' => 'administrador',
            'tipo'=>'gmp',
            'permissions' => json_decode('{"usuarios.index":true,"usuarios.create":true,"usuarios.import":true,"usuarios.store":true,"usuarios.detail":true,"usuarios.update":true,"usuarios.profile":true,"role.index":false,"role.create":false,"role.store":false,"role.show":false,"role.edit":false,"role.update":false,"equipos.index":true,"equipos.create":true,"equipos.create_daily_check":true,"equipos.edit_daily_check":false,"equipos.create_mant_prev":true,"equipos.edit_mant_prev":false,"equipos.create_tecnical_support":true,"equipos.edit_tecnical_support":false,"equipos.start_tecnical_support":false,"equipos.detail":true,"taller":false,"baterias.index":true,"baterias.detail":true,"baterias.register_in_and_out":true,"parteA":true,"parteB":true,"sp.parteA":true,"sp.parteB":false,"sp.parteC":false}')
        ];

        Sentinel::getRoleRepository()->createModel()->fill($adminRole)->save();

        $SupervisorClienteRole = [
            'name' => 'SupervisorC',
            'slug' => 'supervisor-c',
            'tipo'=>'cliente',
            'permissions' =>json_decode('{"usuarios.index":false,"usuarios.create":false,"usuarios.import":false,"usuarios.store":false,"usuarios.detail":false,"usuarios.update":true,"usuarios.profile":true,"role.index":false,"role.create":false,"role.store":false,"role.show":false,"role.edit":false,"role.update":false,"equipos.index":true,"equipos.create":false,"equipos.create_daily_check":false,"equipos.edit_daily_check":true,"equipos.create_mant_prev":false,"equipos.edit_mant_prev":true,"equipos.create_tecnical_support":true,"equipos.detail":true,"taller":false,"baterias.index":false,"baterias.detail":false,"baterias.register_in_and_out":false,"parteA":false,"parteB":true,"sp.parteA":true,"sp.parteB":false,"sp.parteC":false}')];

        Sentinel::getRoleRepository()->createModel()->fill($SupervisorClienteRole)->save();

        $OperadorClienteRole = [
            'name' => 'OperadorC',
            'slug' => 'operador-c',
            'tipo'=>'cliente',
            'permissions' => json_decode('{"usuarios.index":false,"usuarios.create":false,"usuarios.import":false,"usuarios.store":false,"usuarios.detail":false,"usuarios.update":true,"usuarios.profile":true,"equipos.index":true,"equipos.create":false,"equipos.create_daily_check":true,"equipos.create_mant_prev":false,"equipos.edit_mant_prev":false,"equipos.create_tecnical_support":false,"equipos.detail":true,"taller":false,"baterias.index":true,"baterias.detail":true,"baterias.register_in_and_out":true,"parteA":true,"parteB":false}')
        ];

        Sentinel::getRoleRepository()->createModel()->fill($OperadorClienteRole)->save();

        $supervisorGMPRole = [
            'name' => 'Supervisor',
            'slug' => 'supervisor',
            'tipo'=>'gmp',
            'permissions' => jsdon_decode('{"usuarios.index":true,"usuarios.create":true,"usuarios.import":true,"usuarios.store":true,"usuarios.detail":true,"usuarios.update":true,"usuarios.profile":true,"role.index":false,"role.create":false,"role.store":false,"role.show":false,"role.edit":false,"role.update":false,"equipos.index":true,"equipos.create":true,"equipos.create_daily_check":true,"equipos.create_mant_prev":true,"equipos.create_tecnical_support":true,"equipos.detail":true,"taller":false,"baterias.index":false,"baterias.detail":false,"baterias.register_in_and_out":false,"parteA":true,"parteB":false}')
        ];

        Sentinel::getRoleRepository()->createModel()->fill($supervisorGMPRole)->save();

        $tecnicoGMPRole = [
            'name' => 'Tecnico',
            'slug' => 'tecnico',
            'tipo'=>'gmp',
            'permissions' => json_decode('{"usuarios.index":false,"usuarios.create":false,"usuarios.import":false,"usuarios.store":false,"usuarios.detail":false,"usuarios.update":true,"usuarios.profile":true,"role.index":false,"role.create":false,"role.store":false,"role.show":false,"role.edit":false,"role.update":false,"equipos.index":true,"equipos.create":false,"equipos.create_daily_check":false,"equipos.edit_daily_check":false,"equipos.create_mant_prev":true,"equipos.edit_mant_prev":false,"equipos.create_tecnical_support":false,"equipos.edit_tecnical_support":true,"equipos.start_tecnical_support":true,"equipos.detail":true,"taller":false,"baterias.index":false,"baterias.detail":false,"baterias.register_in_and_out":false,"parteA":true,"parteB":false,"sp.parteA":false,"sp.parteB":true,"sp.parteC":true}')
        ];

        Sentinel::getRoleRepository()->createModel()->fill($tecnicoGMPRole)->save();
    }
}
