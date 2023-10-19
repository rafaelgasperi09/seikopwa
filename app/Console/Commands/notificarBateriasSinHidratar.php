<?php

namespace App\Console\Commands;

use App\Formulario;
use App\FormularioRegistro;
use App\Notifications\BateriasNoHidratadas;
use App\User;
use Illuminate\Console\Command;
use DB;
use App\Componente;
use Illuminate\Support\Facades\Mail;
class notificarBateriasSinHidratar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificar:baterias_no_hidratadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que buscar baterias con 15 dias sin hidratar y envia la lista en una notificacion .';

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

        $query="SELECT DISTINCT(componente_id) FROM
                form_carga_bateria_view
                WHERE fecha >=DATE_ADD(NOW(), INTERVAL -60 DAY) AND h2o<>''";
        $hidrated=\DB::select(DB::Raw($query));
        $hidatados=array();
        foreach($hidrated as $key=>$h){
            $hidatados[$key]=$h->componente_id;
        }
        $baterias=Componente::whereTipoComponenteId(2)->whereNotIn('id',$hidatados)->get();
        $datos=array();


        $title = 'Listado de baterias con 15 días sin hidratar.';
        $message='';
        $caracteres=0;
        foreach($baterias as $key=>$b){
            $datos[$key]=array(
                'marca'=>$b->marca,
                'modelo'=>$b->modelo,
                'serie'=>$b->serie,
                'id_componente'=>$b->id_componente,
            );
            $caracteres=strlen($message.$b->id_componente.',');
            if($caracteres>2000)
                break;
            $message.=$b->id_componente.',';
           
        }

        $notificados = User::whereHas('roles',function ($q){
                            $q->where('role_id',5);
                     })->get();

        foreach ($notificados as $user){
            $when = now()->addMinutes(1);
            notifica($user,(new BateriasNoHidratadas($title,$message,route('baterias.index')))->delay($when));
            $this->info($user->getFullName());
            $this->info('body :'.$message);
            $this->info('------------------------------------------');
            if(env('APP_ENV')=='local'){
                break;
            }
        }
    }
}
