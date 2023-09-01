<?php

namespace App\Observers;

use App\Equipo;
use App\File;
use App\Formulario;
use App\FormularioCampo;
use App\FormularioData;
use App\FormularioRegistro;
use App\FormularioRegistroEstatus;
use App\MontacargaConsecutivo;
use App\MontacargaCopiaSolicitud;
use App\MontacargaImagen;
use App\MontacargaSolicitud;
use App\Notifications\TecnicalSupportTicketIsFinnish;
use App\Notifications\DailyCheckIsFinnish;
use App\Notifications\EquipoInoperativo;
use App\Notifications\NewReport;
use App\User;
use App\Supervisor;
use App\SupervisorCli;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Sentinel;

class FormularioRegistroObserver
{
    /**
     * Handle the formulario data "updated" event.
     *
     * @param  \App\FormularioData  $formularioData
     * @return void
     */
    public function created(FormularioRegistro $formularioRegistro)
    {
        FormularioRegistroEstatus::create([
            'formulario_registro_id'=>$formularioRegistro->id,
            'user_id'=>current_user()->id,
            'estatus'=>$formularioRegistro->estatus
        ]);
       
        $formulario = Formulario::find($formularioRegistro->formulario_id);
        $request = request();            
        $when = now()->addMinutes(1);
        $nousar=false;
        $fcampos=$formulario->campos()->orderBy('formulario_seccion_id')->orderBy('orden')->get();
        $roles_form=[];
        $equipo=Equipo::find($formularioRegistro->equipo_id);
        $roles_form[]=1;
        $supervisor_id='';
        if($equipo and substr($equipo->numero_parte,0,2)=='GM'){
            $roles_form[]=5;
        }else{
            if($formulario->tipo=='serv_tec'){
                $roles_form[]=11;
            }
        }
        if(in_array($formulario->tipo,['serv_tec','mant_prev'])){
            $roles_form[]=12;
        }
       
        $lista_noti = Supervisor::whereIn('roles_id',$roles_form)->pluck('id');
        $notificados=User::whereIn('id',$lista_noti)->get();
        //Notifica cuando se crea
        
        foreach ($notificados as $n){
            if(in_array($formulario->tipo,['serv_tec','mant_prev']))
                notifica($n,(new NewReport($formularioRegistro,$n,$notificados))->delay($when));
            if(env('APP_ENV')=='local'){
                break;
            }   
        }
        
        foreach ($fcampos as $campo) {
            $valor = '';
            $user_id = current_user()->id;
            if(!empty($request->second_sign) && $campo->nombre == 'ok_supervisor'){
                $user_id = $request->second_sign;
            }
            if($request->has($campo->nombre))
              $valor = $request->get($campo->nombre);

            if($campo->nombre =="prioridad" && $valor=="No usar este equipo"){
                $nousar=true;
            }
            if($campo->nombre =="supervisor_id"){
                $supervisor_id=$valor;
            }
   
            $files=array();
            if($campo->tipo=='files')  $files = $request->file($campo->nombre);
            
            if($campo->nombre == 'semana') {$valor = Carbon::now()->startOfWeek()->format('d-m-Y');}
            if($campo->nombre == 'dia_semana') {$valor = getDayOfWeek(date('N'));}
            if($campo->tipo=='firma' && $request->get($campo->nombre)){

                $filename = $user_id.'_'.$campo->nombre.'_'.time().'.png';
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                Storage::put('public/firmas/'.$filename,$data);
                $valor =  $filename;
            }
            if($campo->tipo=='montacarga_check' && $request->get($campo->nombre)){

                $filename = $user_id.'_'.$campo->nombre.'_'.time().'.png';
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                Storage::put('public/mccheck/'.$filename,$data);
                $valor =  $filename;
            }
            if(in_array($campo->tipo,['camera','file'])){
                $file = $request->file($campo->nombre);
                
                if($file){
                    $img = Image::make($file->path());
                    $ext = $file->getClientOriginalExtension();
                    if(!empty($formularioRegistro->componente_id)){
                        $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->componente_id.'_'.time().'.'.$ext;
                        $destinationPath = storage_path('/app/public/baterias');
                    }else{
                        $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->equipo_id.'_'.time().'.'.$ext;
                        $destinationPath = storage_path('/app/public/equipos');
                    }
                    $image_info = getimagesize($_FILES[$campo->nombre]["tmp_name"]);
                    $image_width = $image_info[0];
                    $image_height = $image_info[1];
                    $image_height=round(1200*$image_height/$image_width,0);
                    $img->resize(1200, $image_height)->save($destinationPath.'/'.$filename);
                    $valor =  $filename;

                }
            }
            if(is_array($files) and count($files) > 0){
                $j=1;
                foreach ($files as $file){
                    if($file){
                        $img = Image::make($file->path());
                        $ext = $file->getClientOriginalExtension();
                        if(!empty($formularioRegistro->componente_id)){
                            $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->componente_id.'_'.time().$j.'.'.$ext;
                            $folder = 'baterias' ;
                        }else{
                            $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->equipo_id.'_'.time().$j.'.'.$ext;
                            $folder = 'equipos' ;
                        }

                        $destinationPath = storage_path( '/app/public/'.$folder);
                        $img->resize(1200, 1200)->save($destinationPath.'/'.$filename);
                        $valor .=  $filename.',';
                        File::create([
                            'user_id'=>current_user()->id,
                            'tabla'=>'formulario_registro',
                            'registro_id'=>$formularioRegistro->id,
                            'nombre'=>$filename,
                            'ruta'=>'/storage/'.$folder.'/'.$filename
                        ]);

                    }
                    $j++;
                }
            }
            
            if($campo->tipo == 'checkbox'  and $campo->opciones<>''){
                if(!empty($valor) && count($valor)>0)
                    $valor =implode(',',$valor);
            }
           
            $form_data = FormularioData::create([
                'formulario_registro_id' => $formularioRegistro->id,
                'formulario_campo_id' => $campo->id,
                'valor' => $valor,
                'tipo' => $campo->tipo,
                'api_descripcion' => '',
                'user_id'=>$user_id
            ]);
           
            if (!$form_data) {
                throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
            }else{
                //$formularioCampo = FormularioCampo::find($form_data->formulario_campo_id);
               
               
                
                if($campo->cambio_estatus && !empty($form_data->valor)) {
                    
                    if($formularioRegistro<>'C'){
                        $formularioRegistro->estatus = 'C';
                        $formularioRegistro->save();
                    }
                   
                    if(env('APP_ENV')!='local' or true){
                        //NOTIFICA SUPERVISORES GMP
                        foreach ($notificados as $n){
                            $when = now()->addMinutes(1);
                            if($campo->formulario->nombre=='form_montacarga_servicio_tecnico')
                                notifica($n,(new TecnicalSupportTicketIsFinnish($formularioRegistro))->delay($when));
                            if($campo->formulario->nombre=='form_montacarga_daily_check'){
                                //notifica($n,(new DailyCheckIsFinnish($formularioRegistro))->delay($when));
                                //notifica($n,(new EquipoInoperativo($formularioRegistro))->delay($when));
                                if($nousar){
                                    notifica($n,(new EquipoInoperativo($formularioRegistro))->delay($when));
                                }
                            }
                            if(env_local()){
                                break;
                            }   
                        }
                        
                    }

                    //NOTIFICA SUPERVISORES CLIENTES
                  

                    $notificadosCli = User::whereHas('roles',function ($q){
                                            $q->where('role_id',3);
                                        })
                                        ->whereRaw("(crm_clientes_id ='$equipo->cliente_id' or crm_clientes_id like '%,$equipo->cliente_id%' or crm_clientes_id like '%$equipo->cliente_id,%')")
                                        ->when(!empty($supervisor_id),function($q) use($supervisor_id){
                                            $q->where('id',$supervisor_id);
                                        })    
                                        ->get();
                    
                    if($campo->formulario->nombre=='form_montacarga_daily_check'){
                        foreach ($notificadosCli as $u){
                                notifica($u,(new DailyCheckIsFinnish($formularioRegistro,$u,$notificadosCli))->delay($when));
                                if(env_local()){
                                    break;
                                }
                        }
                    }
                 
                    
                    // si es matenimiento preventivo crear solicitud en crm de montacarga
                }
            }
        }
    }
    /**
     * Handle the formulario data "updated" event.
     *
     * @param  \App\FormularioData  $formularioData
     * @return void
     */
    public function updated(FormularioRegistro $formularioRegistro)
    {
        $request = request();     
    
        if($formularioRegistro->isDirty('estatus') or $formularioRegistro->isDirty('tecnico_asignado')){
            FormularioRegistroEstatus::create([
                'formulario_registro_id'=>$formularioRegistro->id,
                'user_id'=>current_user()->id,
                'estatus'=>$formularioRegistro->estatus
            ]);
      
        }else{
            $formulario = Formulario::find($formularioRegistro->formulario_id);

              //  DB::transaction(function () use ($request, $formulario,$formularioRegistro) {
                    $equipo=Equipo::find($formularioRegistro->equipo_id);
                    $roles_form=array();
                    $when = now()->addMinutes(1);
                    $nousar=false;
                    $fcampos=$formulario->campos()->orderBy('formulario_seccion_id')->orderBy('orden')->get();
                    //dd($fcampos);
                    foreach ($fcampos as $campo) {
                        if($request->has($campo->nombre) and !empty($request->get($campo->nombre))){
                            
                            $valor = $request->get($campo->nombre);
        
                            if($campo->nombre =="prioridad" && $valor=="No usar este equipo"){
                                $nousar=true;
                            }
        
                            $files=array();
                            if($campo->tipo=='files' and $request->file($campo->nombre)) {
                                $files = $request->file($campo->nombre);
                            }
                            
                            if(!empty($valor) or count($files) > 0){
        
                                if($campo->tipo=='firma'){
                                    $filename = Sentinel::getUser()->id.'_'.$campo->nombre.'_'.time().'.png';
                                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                                    Storage::put('public/firmas/'.$filename,$data);
                                    $valor =  $filename;
                                }
                                if($campo->tipo=='montacarga_check' && $request->get($campo->nombre)){

                                    $filename = $user_id.'_'.$campo->nombre.'_'.time().'.png';
                                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                                    Storage::put('public/mccheck/'.$filename,$data);
                                    $valor =  $filename;
                                }
                                if(in_array($campo->tipo,['camera','file'])){
                                    $file = $request->file($campo->nombre);
                                
                                    if($file){
                                        $img = Image::make($file->path());
                                        $ext = $file->getClientOriginalExtension();
                                        $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->equipo_id.'_'.time().'.'.$ext;
                                    
                                        $destinationPath = storage_path('/app/public/equipos');
                                        $img->resize(800,null, function ($constraint) {
                                            $constraint->aspectRatio();
                                        })->save($destinationPath.'/'.$filename);
                                        $valor =  $filename;
                                    
                                    }
                                }
                                if(isset($files)){
                                    if(count($files) > 0){
                                        $j=1;
                                        foreach ($files as $file){
                                            if($file){
                                                $img = Image::make($file->path());
                                                $ext = $file->getClientOriginalExtension();
                                                if(!empty($formularioRegistro->componente_id)){
                                                    $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->componente_id.'_'.time().$j.'.'.$ext;
                                                    $folder = 'baterias' ;
                                                }else{
                                                    $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->equipo_id.'_'.time().$j.'.'.$ext;
                                                    $folder = 'equipos' ;
                                                }
        
                                                $destinationPath = storage_path( '/app/public/'.$folder);
                                                $img->resize(800,null, function ($constraint) {
                                                    $constraint->aspectRatio();
                                                })->save($destinationPath.'/'.$filename);
                                                $valor .=  $filename.',';
                                                
                                                File::create([
                                                    'user_id'=>current_user()->id,
                                                    'tabla'=>'formulario_registro',
                                                    'registro_id'=>$formularioRegistro->id,
                                                    'nombre'=>$filename,
                                                    'ruta'=>'/storage/'.$folder.'/'.$filename
                                                ]);
        
                                            }
                                            $j++;
                                        }
        
                                    }
                                }
        
                                if($campo->nombre == 'hora_salida'){
                                    $valor = date('H:i');
                                }
                                if($campo->tipo == 'checkbox'  and $campo->opciones<>''){
                                    if(!empty($valor) && count($valor)>0)
                                        $valor =implode(',',$valor);
                                }
                                $fd_item = FormularioData::whereFormularioRegistroId($formularioRegistro->id)->whereFormularioCampoId($campo->id)->first();
                                if(!$fd_item){
                                    $fd_item =FormularioData::create([
                                        'formulario_registro_id' => $formularioRegistro->id,
                                        'formulario_campo_id' => $campo->id,
                                        'tipo' => $campo->tipo,
                                        'api_descripcion' => '',
                                        'user_id'=>current_user()->id
                                    ]);
                                }
                                if($fd_item){
                                    $form_data = FormularioData::find($fd_item->id);
                                    $form_data->valor = $valor;
                                    $form_data->user_id = current_user()->id;
                                    if (!$form_data->save()) {
                                        throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
                                    }else{
                                        $formularioCampo = FormularioCampo::find($form_data->formulario_campo_id);
                                    
                                        if($formularioCampo->cambio_estatus && isset($form_data->valor)) {
                                            
                                            $formularioRegistro->estatus = 'C';
                                            
                                            $formularioRegistro->save();
                                            // si es matenimiento preventivo crear solicitud en crm de montacarga
            
                                            // si es soporte tecnico notificar a los usuarios dependendiendo de los departamentos
                                            // buscar usuarios con rol supervisor GMP
                                            if($equipo and substr($equipo->numero_parte,0,2)=='GM'){
                                                $roles_form[]=5;
                                            }
                                            if($formulario->tipo=='serv_tec'){
                                                $roles_form[]=11;
                                                $roles_form[]=12;
                                            }
                                            if($formulario->tipo=='mant_prev'){
                                                $roles_form[]=12;
                                            }
                                            $lista_noti = Supervisor::whereIn('roles_id',$roles_form)->pluck('id');
                                            $notificados=User::whereIn('id',$lista_noti)->get();

                                            // $notificados = User::where('id',$formularioRegistro->creado_por)->get();
                                        
                                            foreach ($notificados as $n){
                                                $when = now()->addMinutes(1);
                                                if($formularioCampo->formulario->nombre=='form_montacarga_servicio_tecnico')
                                                    notifica($n,(new TecnicalSupportTicketIsFinnish($formularioRegistro))->delay($when));
                                                if($formularioCampo->formulario->nombre=='form_montacarga_daily_check'){
                                                // notifica($n,(new DailyCheckIsFinnish($formularioRegistro))->delay($when));
                                                    if($nousar){
                                                        notifica($n,(new EquipoInoperativo($formularioRegistro))->delay($when));
                                                    }
                                                }
                                                if(env('APP_ENV')=='local'){
                                                    break;
                                                }
                                            }

                                            if($formularioCampo->formulario->nombre=='form_montacarga_daily_check'){
                                                    $supervisor_id=$formularioRegistro->data()->where('formulario_campo_id',1069)->first();
                                                    if($supervisor_id->valor){
                                                        $supervisor_id=$supervisor_id->valor;
                                                    }else{
                                                        $supervisor_id='';
                                                    }
                                                
                                                    $notificadosCli = User::whereHas('roles',function ($q){
                                                                                $q->where('role_id',3);
                                                                            })
                                                                            ->whereRaw("(crm_clientes_id ='$equipo->cliente_id' or crm_clientes_id like '%,$equipo->cliente_id%' or crm_clientes_id like '%$equipo->cliente_id,%')")
                                                                            ->when(!empty($supervisor_id),function($q) use($supervisor_id){
                                                                                $q->where('id',$supervisor_id);
                                                                            })->get();
                                                   
                                                    foreach ($notificadosCli as $n){
                                                            notifica($n,(new DailyCheckIsFinnish($formularioRegistro,$n,$notificadosCli))->delay($when));
                                                            if(env_local()){
                                                                break;
                                                            }
                                                    }
                                            }
                                        }
                                    }
                                }

                            }
                        } 
                    }
             /*   });
            }catch (\Exception $e){
                $request->session()->flash('message.error', $e->getMessage());
                return back()->withInput();
            }*/
        }

    }

}