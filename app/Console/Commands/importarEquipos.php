<?php

namespace App\Console\Commands;

use App\Cliente;
use App\MontacargaUser;
use App\User;
use App\EquiposVw;
use App\ClientesVw;
use App\Equipo;
use Sentinel;
Use DB;
use Illuminate\Console\Command;


class ImportarEquipos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:equipos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para importa los clientes y equipos en una tabla local';

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
        $dataQuery='SELECT
        e.id           AS id,
        e.numero_parte AS numero_parte,
        e.modelo       AS modelo,
        e.serie        AS serie,
        c.descripcion  AS cliente,
        e.updated_at 
      FROM (montacarga.equipos e
         JOIN montacarga.contactos c)
      WHERE e.cliente_id = c.id ';
        $data=\DB::connection('crm')->select(DB::Raw($dataQuery));
        $data=json_decode(json_encode($data), true);
        $registros=EquiposVw::get()->count();
        foreach($data as $d){
            if($d['updated_at']>=date('Y-m-d',strtotime("-1 days")) or $registros==0){
                $eq=EquiposVw::find($d['id']);
                if($eq){
                    $eq->delete();
                    $this->info("------------------BORRANDO EQUIPO ".$d['numero_parte']."-------------------");
                }
                $obj =  EquiposVw::create($d);
                if($obj){
                    $this->info("------------------INSERTADO EQUIPO ".$d['numero_parte']."-------------------");
                }
            }
          

        }

        //importar clientes
        $dataQuery='SELECT
                        c.id,
                        c.nombre,
                        c.updated_at
                    FROM montacarga.contactos c';
        $data=\DB::connection('crm')->select(DB::Raw($dataQuery));
        $data=json_decode(json_encode($data), true);
        $registros=ClientesVw::get()->count();
        foreach($data as $d){
            if($d['updated_at']>=date('Y-m-d',strtotime("-1 days"))  or $registros==0){
                $cl=ClientesVw::find($d['id']);
                if($cl){
                    $cl->delete();
                    $this->info("------------------BORRANDO CLIENTE ".$d['nombre']."-------------------");
                }
                $obj =  ClientesVw::create($d);
                if($obj){
                    $this->info("------------------INSERTADO CLIENTE ".$d['nombre']."-------------------");
                }
            }
          

        }
    }
}
