<?php

namespace App\Console\Commands;

use App\Formulario;
use App\FormularioRegistro;
use App\Notifications\DailyCheck;
use App\User;
use Illuminate\Console\Command;

class notificarChequeoDiario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificar:chequeo_diario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que buscar todos los clientes con todos sus eqipos y ve si hay alguno que no se le ha hecho el daily check para asi mandarle una notificacion.';

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

        $this->info('------------------START CHEQUEO DIARIO JOB -------------------');
        $notificados['users'] = array();
        $turnos=array('07'=>1,'15'=>2,'22'=>3,'00'=>4);
        $turno=1;
        if(isset($turnos[date('H')]))
        $turno=$turnos[date('H')];
        foreach (User::whereNotNull('crm_clientes_id')->get() as $user){
           
            if(date('D') <> 'Sun'){
                $clientes = $user->clientes();
                //dd(count($clientes));
                $form = Formulario::whereNombre('form_montacarga_daily_check')->first();
                foreach($clientes as $cliente){
                    // verificar si el cliente tiene al menos un equipo en su poder
                    if($cliente->equipos()->count()> 0){  //tiene al menos un equipo verificar si ya hizo el chequeo diario de todos
                        //
                        foreach ($cliente->equipos()->get() as $equipo){  
                            if(!FormularioRegistro::whereRaw("date_format(created_at,'%Y-%m-%d') = '".date('Y-m-d')."'")
                                ->whereClienteId($cliente->id)
                                ->whereEquipoId($equipo->id)
                                ->where('turno_chequeo_diario',$turno)
                                ->whereFormularioId($form->id)
                                ->first()){

                                $notificados['users'][$user->id]['id'] =$user->id;
                                $notificados['users'][$user->id]['cliente_id'] =$cliente->id;
                                $notificados['users'][$user->id]['cliente'] =$cliente->nombre;
                                $notificados['users'][$user->id]['equipos'][$equipo->id]['equipo_id'] =$equipo->id;
                                $notificados['users'][$user->id]['equipos'][$equipo->id]['equipo'] =$equipo->numero_parte;
                            }
                        }

                    }   
                }

            }


            
        }

        $title = 'Tiene equipos sin chequeo diario.';
        foreach ($notificados['users'] as $noti){
            $tot =0;
            $user = User::find($noti['id']);
            $message = 'Equipos :';
            foreach ($noti['equipos'] as $e){
           
                $message .= $e['equipo'].'|';
                $tot++;
            }
            $when = now()->addMinutes(1);
            
            notifica($user,(new DailyCheck($title,$message,route('equipos.index')))->delay($when));
            $this->info($noti["cliente"]);
            $this->info($user->email);
            $this->info('body :'.$message);
            $this->info('------------------------------------------');
            if(env('APP_ENV')=='local'){
                break;
            }
        }
    }
}
