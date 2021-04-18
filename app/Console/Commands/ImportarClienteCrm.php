<?php

namespace App\Console\Commands;

use App\Cliente;
use App\MontacargaUser;
use App\User;
use Sentinel;
use Illuminate\Console\Command;

class ImportarClienteCrm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:clientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para importa los clientes creados en el crm a usuarios en nuestra base de datos con rol cliente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Cliente::whereNotNull('correo')->get() as $cliente){

            if(filter_var($cliente->correo, FILTER_VALIDATE_EMAIL)) {    // valid address
                if(!User::whereEmail($cliente->correo)->first()){ // si no existe crearlo como usuario

                    $user = Sentinel::registerAndActivate(array(
                        'email' => $cliente->correo,
                        'first_name' => $cliente->nombre,
                        'password' => 'GMPAPP2021',
                    ));

                    $role = Sentinel::findRoleById(3); // rol cliente
                    $role->users()->attach($user);
                    $user->have_to_change_password = 1;
                    $user->crm_cliente_id = $cliente->id;

                    if($user->save()){
                       $this->info('Cliente '.$cliente->nombre.' creado con exito como usuario.');
                    }

                }else{
                    $this->error('El cliente con el correo '.$cliente->correo.' ya existe como usuario.');
                }
            }
            else {
               $this->error('Correo '.$cliente->correo.' invalido');
            }

        }
    }
}
