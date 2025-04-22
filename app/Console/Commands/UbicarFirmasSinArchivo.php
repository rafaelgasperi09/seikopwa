<?php

namespace App\Console\Commands;

use App\Cliente;
use App\User;
use App\FormularioData;
use App\ClientesVw;
use App\Equipo;
use Sentinel;
Use DB;
use Illuminate\Console\Command;


class UbicarFirmasSinArchivo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buscar_firmas:sin_archivo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para busca firmas sin ubicar el archivo';

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
       // $dataQuery="SELECT * FROM `formulario_data` WHERE tipo='firma' AND LENGTH(valor)>3 AND file_path IS NULL";
        $data=FormularioData::where('tipo','firma')->whereNull('file_path')->whereRaw("LENGTH(valor)>3")->get();
        $k=0;$r=0;
        foreach($data as $d){
            if(file_exists( storage_path('app/public/firmas/'.$d->valor))){
                $d->file_path=$d->valor;
                $d->save();
            }else{
                $filename=$d->valor;
                $filename=explode('_',$filename);
                $filename[count($filename)-1]='';
                $filename=implode('_',$filename);
                $file=FormularioData::where('tipo','firma')->whereNotNull('file_path')->whereRaw("LENGTH(valor)>3 and valor like '$filename%'")->inRandomOrder()->first();
                if($file){
                    $r++;
                    copy( storage_path('app/public/firmas/'.$file->valor), storage_path('app/public/firmas/'.$d->valor));
                }
                
               // $this->info("------------------NO SE ENCONTRO PARA EL REPORTE ".$d->formulario_registro_id."-------------------");
               // dd($d->formulario_registro_id);
            }
            
            $k++;
            //if($k%100==1)
                  
            

        }
        $this->info("------------------ SE ENCONTRARON $k REPORTES SIN ARCHIVO Y SE REEMPLAZARON $r -------------------");
    }
}
