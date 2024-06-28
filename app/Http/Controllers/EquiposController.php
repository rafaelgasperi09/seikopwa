<?php

namespace App\Http\Controllers;
use App\Formulario;
use App\FormularioData;
use App\FormularioRegistro;
use App\FormularioRegistroEstatus;
use App\Http\Requests\SaveFormEquipoRequest;
use App\MontacargaConsecutivo;
use App\MontacargaCopiaSolicitud;
use App\MontacargaImagen;
use App\MontacargaSolicitud;
use App\Notifications\NewReport;
use App\Notifications\NewTecnicalSupportAssignTicket;
use App\Notifications\NewTecnicalSupport;
use App\User;
use App\Supervisor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\TipoEquipo;
use App\Equipo;
use App\Cliente;
use App\SubEquipo;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Sentinel;
use Illuminate\Support\Facades\Storage;
use PDF;
use Response;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportesExport;

class EquiposController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $subEquipos=SubEquipo::orderBy('id','desc')->get();

        $tipoEquiposElectricos = Equipo::select('equipos.sub_equipos_id','equipos.tipo_equipos_id','tipo_equipos.display_name')
                         ->FiltroCliente()
                         ->join('tipo_equipos','equipos.tipo_equipos_id','=','tipo_equipos.id')
                         ->groupBy('equipos.sub_equipos_id','equipos.tipo_equipos_id')
                         ->where('equipos.sub_equipos_id','=',2)
                         ->whereNotNull('equipos.tipo_equipos_id')
                         ->get();
        $tipoEquiposArray=array();
        foreach($tipoEquiposElectricos as $t){
            $tipoEquiposArray[$t->sub_equipos_id][$t->tipo_equipos_id]=$t->display_name;
            $tipoEquiposArray[$t->sub_equipos_id][$t->tipo_equipos_id]=$t->display_name;
        }

        $tipoEquiposCombustion = Equipo::select('equipos.sub_equipos_id','equipos.tipo_motore_id','tipo_motores.display_name')
            ->FiltroCliente()
            ->join('tipo_motores','equipos.tipo_motore_id','=','tipo_motores.id')
            ->groupBy('equipos.sub_equipos_id','equipos.tipo_motore_id')
            ->where('equipos.sub_equipos_id','=',1)
            ->get();

        foreach($tipoEquiposCombustion as $t){
            $tipoEquiposArray[$t->sub_equipos_id][$t->tipo_motore_id]=$t->display_name;
            $tipoEquiposArray[$t->sub_equipos_id][$t->tipo_motore_id]=$t->display_name;
        }

        return view('frontend.equipos.index')->with('tipos',$tipoEquiposArray)->with('subEquipos',$subEquipos);

    }

    public function lista(Request $request){
        $filtro='';
        $dominio=$request->dominio;
        $cliente_id=$request->cliente_id;
        if(in_array($dominio,['cliente','gmp'])){
            if($dominio=='gmp'){
                $filtro="SUBSTR(equipos.numero_parte,1,2)='GM'";
            }else{
                $filtro="SUBSTR(equipos.numero_parte,1,2)<>'GM'";
            }
        }
        if($request->has('cliente_id') and !empty($request->cliente_id)){
            if($filtro<>'')
                $filtro.=" and cliente_id=$request->cliente_id";
            else
                $filtro.=" cliente_id=$request->cliente_id";
        }
        $equipos=Equipo::selectRaw('equipos.*')->FiltroCliente()
        ->leftJoin('contactos','equipos.cliente_id','=','contactos.id')
        ->when($filtro<>'',function($q) use($filtro){
            $q->whereRaw($filtro);
        })->paginate(10);
        $clientes=current_user()->crm_clientes_id;
        $clientes=clientes_string($clientes);
        if(!empty($clientes)){
            $clientes=Cliente::whereRaw("(id in($clientes))")->orderBy('nombre')->get()->pluck('nombre','id');
        }else{
            $clientes_reportes=DB::Select(DB::Raw("SELECT cliente_id FROM formulario_registro 
            WHERE cliente_id IS NOT NULL AND deleted_at IS NULL
            GROUP BY cliente_id"));
            $clientes_reportes=collect($clientes_reportes)->pluck('cliente_id');
            $clientes=Cliente::whereIn('id',$clientes_reportes)->orderBy('nombre')->get()->pluck('nombre','id');
        }
        $clientes['']='Seleccione el cliente';
        $datos=array('sub'=>'todos',
            'tipo'=>'todos',
            'subName'=>'Lista',
            'tipoName'=>'Todos');
        return view('frontend.equipos.lista')->with('equipos',$equipos)->with('datos',$datos)->with('dominio',$dominio)->with('clientes',$clientes)->with('cliente_id',$cliente_id);
    }

    public function reportes_list(Request $request){
        $filtro='false';
        return view('frontend.equipos.reportes')->with('filtro',$filtro);
    }

    public function reportes_datatable(Request $request,$export_datos=false){
        //dd($request->all());
        $clientes=explode(',',current_user()->crm_clientes_id);
        $editar=(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'));
        $carbon = new \Carbon\Carbon();
        $desde = $carbon->now()->subDays(45)->format('Y-m-d'); //filtro reportes cerrados 45 dias
        $es_cliente=current_user()->isCliente();
        $data = DB::table('formulario_registro as fr')
                ->join('formularios as f','fr.formulario_id','f.id')
                ->join('users as u','fr.creado_por','u.id')
                ->join('equipos_vw as evw','fr.equipo_id','evw.id')
                ->join('clientes_vw as cvw','fr.cliente_id','cvw.id')
                ->leftjoin('formulario_data as fd','fr.id','fd.formulario_registro_id')
                ->join('formulario_campos as fc','fd.formulario_campo_id','fc.id')
                ->join('users as usr','fd.user_id','usr.id')     
                ->selectRaw("  fr.id, 
                                fr.created_at, 
                                fr.fecha_inicia, 
                                fr.fecha_fin, 
                                fr.estatus, 
                                fr.equipo_id, 
                                fr.turno_chequeo_diario, 
                                u.first_name, 
                                u.last_name, 
                                f.tipo, 
                                cvw.nombre AS cliente_nombre, 
                                evw.numero_parte, 
                                CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                                MAX(CASE WHEN fc.tipo = 'firma' AND fc.cambio_estatus = 1 AND fd.valor IS NOT NULL THEN CONCAT(usr.first_name, ' ', usr.last_name) ELSE '' END) AS cliente, 
                                MAX(CASE WHEN fd.formulario_campo_id IN (968, 969) THEN fd.valor ELSE '' END) AS prioridad, 
                                MAX(CASE WHEN fc.nombre IN ('horometro', 'lectura_horometro') AND fc.tipo = 'number' THEN fd.valor ELSE '' END) AS horometro")
                ->whereNull('fr.deleted_at')
                //->whereRaw("(formulario_registro.estatus='C' and formulario_registro.created_at >='$desde' or formulario_registro.estatus<>'C')")
                ->when(current_user()->isCliente() ,function ($q) use($request,$clientes){
                    $q->whereIn("cliente_id",$clientes);                    
                })
                ->when(!empty($request->equipo_id) and $request->equipo_id>0 ,function ($q) use($request){
                    $q->where("equipo_id",$request->equipo_id);
                })
                ->when(!empty($request->cliente_id)  ,function ($q) use($request){
                    $q->where("cliente_id",$request->cliente_id);
                })
                ->when(!empty($request->desde)  ,function ($q) use($request){
                    $q->where("fr.created_at",'>=',$request->desde);
                })
                ->when(!empty($request->hasta)  ,function ($q) use($request){
                    $q->where("fr.created_at",'<=',$request->hasta);
                })
                ->when(!empty($request->tipo)  ,function ($q) use($request){
                    $q->where("f.tipo",$request->tipo);
                })
                ->when(!empty($request->estado)  ,function ($q) use($request){
                    $q->where("fr.estatus",$request->estado);
                })
                ->when(!empty($request->created_by)  ,function ($q) use($request){
                    $q->where("fr.creado_por",$request->created_by);
                })
                ->groupBy(DB::raw('fr.id, fr.created_at, fr.fecha_inicia, fr.fecha_fin, fr.estatus, fr.equipo_id,
                fr.turno_chequeo_diario,u.first_name, u.last_name, f.tipo, cvw.nombre, evw.numero_parte, u.first_name, u.last_name'));
              
    if($export_datos){
        return $data->get();
    }
    return DataTables::of($data)
        ->editColumn('creado_por', function($row) {
            return $row->first_name.' '.$row->last_name;
        })
        ->editColumn('created_at', function($row) {
            return \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
        })
        ->editColumn('numero_parte', function($row) {
            return "<a target='_blank' href='".route('equipos.detail',$row->equipo_id)."'>$row->numero_parte</a>";
        })
        ->editColumn('cliente', function($row){
            if($row->cliente<>$row->user_name)
                return $row->cliente;
            return '';
        })
        ->editColumn('tipo', function($row) {
            return tipo_form($row->tipo);
        })
        ->addColumn('actions', function($row) use($editar) {
        $url='';
        $url2='';
        $url_edit='';
        $puedo_imprimir=true;
        if($row->tipo=='daily_check'){
            $url=route('equipos.show_daily_check',$row->id);
            $url2=route('reporte.detalle',['form_montacarga_daily_check',$row->id]);   
            $url_edit=route('equipos.edit_daily_check',$row->id);
        }
          
        if($row->tipo=='mant_prev'){
            $url=route('equipos.show_mant_prev',$row->id);  
            $url2=route('equipos.imprimir_mant_prev',$row->id);  
            $url_edit=route('equipos.edit_mant_prev',$row->id);  
            if($row->estatus<>'C')
                $puedo_imprimir=false;
        }
        if($row->tipo=='serv_tec'){
            $url=route('equipos.show_tecnical_support',$row->id);
            $url2=route('reporte.detalle',['form_montacarga_servicio_tecnico',$row->id]); 
            $url_edit=route('equipos.edit_tecnical_support',$row->id);  
        }
            $ret='';
            if($editar){
                $ret.= ' <a target="_blank" href="'.$url_edit.'" target="_blank" class="btn btn-success btn-sm mr-1" title="Editar">
                            <ion-icon name="pencil-outline" ></ion-icon>
                        </a>';
            }
            $ret.= ' <a target="_blank" href="'.$url.'" target="_blank" class="btn btn-primary btn-sm mr-1 " title="Ver detalle">
                        <ion-icon name="eye-outline" ></ion-icon>';
            if($puedo_imprimir){
                $ret.= ' </a>
                <a target="_blank" href="'.$url2.'" target="_blank" class="btn btn-warning btn-sm mr-1 "  title="Imprimir formulario">
                    <ion-icon name="file-tray-stacked-outline"></ion-icon>
                </a>';
            }            
    
            return $ret;
        })
        ->rawColumns(['status','numero_parte', 'cliente','actions'])
        ->toJson();

    }

    public function reportes_export(Request $request){
        $file='reportes.csv';
       
        $datos=$this->reportes_datatable($request,true);
      
        $headers = array(
            "Content-Encoding"        => "UTF-8",
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$file",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
            //cabecera
        $columns=array('IDREPORTE','FECHA REGISTRO','HORA', 'TIPO','EQUIPO','PRIORIDAD','REGISTRADO POR','CLIENTE','SEMANA','DIA','HOROMETRO','ESTATUS','TURNO');
                        
        $campos=array('id','fecha','hora','tipo','numero_parte','prioridad','user_name','nombre','semana','dia_semana','horometro','estatus','turno_chequeo_diario');
        $i=0;
        return Excel::download(new ReportesExport($datos), 'Equipo.xlsx');
        /*
        foreach($datos as $value){
            foreach($value as $k=>$v){
                if($i++<=31)
                    array_push($columns,$k);
            }
            break;
        }
        */
        $callback = function() use($datos,$file,$columns,$campos) {

            $file = fopen('php://output', 'w');
            
            fputcsv($file, $columns);
          
            foreach($datos as $value){
                $rows=array();
                foreach($campos as $c){
                    $d= preg_replace("[\n|\r|\n\r]", " ", utf8_decode($value->$c));
                    array_push($rows,$d);
                }
                fputcsv($file, $rows);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
        exit();
    }

    public function tipo($sub,$id){

        $datos=array('sub'=>$sub,
                     'tipo'=>$id,
                     'subName'=>getSubEquipo($sub,'name'),
                     'tipoName'=>getTipoEquipo($id,$sub));

        if($id=='todos'){
            $equipos=Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo($sub))->paginate(10);
        }
        else if($sub=='electricas') {
            $equipos=Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo($sub))->where('tipo_equipos_id',$id)->paginate(10);
        }else{
            $equipos=Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo($sub))->whereNull('tipo_equipos_id')->where('tipo_motore_id',$id)->paginate(10);
        }

        return view('frontend.equipos.index')->with('equipos',$equipos)->with('datos',$datos);
    }

    public function search(Request $request,$sub,$id){
        
        if($id=='todos' and $sub=='todos'){

            $filtro='';
            $dominio=$request->dominio;
            if(in_array($dominio,['cliente','gmp'])){
                if($dominio=='gmp'){
                    $filtro="SUBSTR(equipos.numero_parte,1,2)='GM'";
                }else{
                    $filtro="SUBSTR(equipos.numero_parte,1,2)<>'GM'";
                }
            }
            if($request->has('cliente_id') and !empty($request->cliente_id)){
                if($filtro<>'')
                    $filtro.=" and cliente_id=$request->cliente_id";
                else
                    $filtro.=" cliente_id=$request->cliente_id";
            }
            $equipos=Equipo::selectRaw('equipos.*')->FiltroCliente()
            ->leftJoin('contactos','equipos.cliente_id','=','contactos.id')
            ->when($filtro<>'',function($q) use($filtro){
                $q->whereRaw($filtro);
            })
            ->whereRaw("(numero_parte like '%".$request->q."%' or contactos.nombre like '%".$request->q."%')")
            ->paginate(10);
        }

        if($id=='todos' and $sub!='todos'){
            $equipos=Equipo::selectRaw('equipos.*')->FiltroCliente()
                ->leftJoin('contactos','equipos.cliente_id','=','contactos.id')
                ->where('sub_equipos_id',getSubEquipo($sub))
                ->whereRaw("(numero_parte like '%".$request->q."%' or contactos.nombre like '%".$request->q."%')")
                ->paginate(10);
        }else
        if(getSubEquipo($sub)==2){
            $equipos=Equipo::selectRaw('equipos.*')->FiltroCliente()
                ->leftJoin('contactos','equipos.cliente_id','=','contactos.id')
                ->where('sub_equipos_id',getSubEquipo($sub))
                ->where('tipo_equipos_id',$id)
                ->whereRaw("(numero_parte like '%".$request->q."%' or contactos.nombre like '%".$request->q."%')")
                ->paginate(10);
        }else if(getSubEquipo($sub)==1){
            $equipos=Equipo::selectRaw('equipos.*')->FiltroCliente()
                ->leftJoin('contactos','equipos.cliente_id','=','contactos.id')
                ->where('sub_equipos_id',getSubEquipo($sub))
                ->where('tipo_motore_id',$id)
                ->whereRaw("(numero_parte like '%".$request->q."%' or contactos.nombre like '%".$request->q."%')")
                ->paginate(10);
        }
        return view('frontend.equipos.page')->with('data',$equipos);
    }

    public function detail(Request $request,$id){
        $data = Equipo::findOrFail($id);
        $ver=current_user()->can('see',$data);
        
        if(!current_user()->can('see',$data)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }
        $dow=array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $dias=array();
        foreach($dow as $d){
            for($i=1;$i<=4;$i++){
                $dias[]=$d.$i;
            }
        }
        $querySelect="formulario_registro.semana,formulario_registro.ano,max(formulario_registro.id) as id,".PHP_EOL;
        foreach($dias as $k=>$dia){
            $querySelect.="MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN '$dia' THEN concat(formulario_registro.id,'_',formulario_data.valor) ELSE '' END) AS $dia";
            if($k+1<count($dias))
                $querySelect.=','.PHP_EOL;
        }
        $user=current_user();
        $form['dc'] =FormularioRegistro::selectRaw($querySelect)
                                        ->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->join('formulario_data','formulario_data.formulario_registro_id','=','formulario_registro.id')
                                        ->where('formulario_data.formulario_campo_id',968)
                                        ->where('equipo_id',$id)
                                        ->where('formularios.nombre','form_montacarga_daily_check')
                                        ->groupBy('formulario_registro.semana','formulario_registro.ano')
                                        ->when($user->isCliente(),function($q) use($user){
                                            $q->whereRaw('formulario_registro.cliente_id in ('.$user->crm_clientes_id.')');
                                        })
                                        ->get();

        $form['st']=FormularioRegistro::selectRaw('formulario_registro.*')->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->where('equipo_id',$id)
                                        ->where('formularios.tipo','serv_tec')
                                        ->when($user->isCliente(),function($q) use($user){
                                            $q->whereRaw('formulario_registro.cliente_id in ('.$user->crm_clientes_id.')');
                                        })
                                        ->get();


        $form['mp']=FormularioRegistro::selectRaw('formulario_registro.*')->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->where('equipo_id',$id)->where('formularios.tipo','mant_prev')
                                        ->when($user->isCliente(),function($q) use($user){
                                            $q->whereRaw('formulario_registro.cliente_id in ('.$user->crm_clientes_id.')');
                                        })
                                        ->get();

        $form['ca']=FormularioRegistro::selectRaw('formulario_registro.*')->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->where('equipo_id',$id)->where('formularios.tipo','control_alquiler')
                                        ->when($user->isCliente(),function($q) use($user){
                                            $q->whereRaw('formulario_registro.cliente_id in ('.$user->crm_clientes_id.')');
                                        })
                                        ->get();


        //dd($data->tipo->name);
        $mostrar=array('det'=>'show','reg'=>'');
        if($request->get('show')=='rows'){
            $mostrar['det']='';
            $mostrar['reg']='show';
        }

        $tab=array('t1'=>'active','t2'=>'','t3'=>'','t4'=>'');
        $tab_content=array('t1'=>'active show','t2'=>'','t3'=>'','t4'=>'');
        if(!\Sentinel::hasAnyAccess(['equipos.see_daily_check','equipos.edit_daily_check'])){
            $tab['t1']=''; $tab['t2']='active';
            $tab_content['t1']=''; $tab['t2']='active show';
        }

        if($request->get('tab')== 1){
            $tab=array('t1'=>'active','t2'=>'','t3'=>'','t4'=>'');
            $tab_content=array('t1'=>'active show','t2'=>'','t3'=>'','t4'=>'');
        }

        if($request->get('tab')== 2){
            $tab=array('t1'=>'','t2'=>'active','t3'=>'','t4'=>'');
            $tab_content=array('t1'=>'','t2'=>'active show','t3'=>'','t4'=>'');
        }

        if($request->get('tab')== 3){
            $tab=array('t1'=>'','t2'=>'','t3'=>'active','t4'=>'');
            $tab_content=array('t1'=>'','t2'=>'','t3'=>'active show','t4'=>'');
        }
        if($request->get('tab')== 4){
            $tab=array('t1'=>'','t2'=>'','t3'=>'','t4'=>'active');
            $tab_content=array('t1'=>'','t2'=>'','t3'=>'','t4'=>'active show');
        }

        if($data->sub_equipos_id==1){
            $route_back = route('equipos.tipo',['sub'=>$data->subTipo->name,'id'=>$data->tipo_motore_id]);
        }else{
            $route_back = route('equipos.tipo',['sub'=>$data->subTipo->name,'id'=>$data->tipo_equipos_id]);
        }


        return view('frontend.equipos.detail')->with('data',$data)
            ->with('route_back',$route_back)
            ->with('form',$form)->with('mostrar',$mostrar)
            ->with('tab',$tab)
            ->with('dias',$dias)
            ->with('dow',$dow)
            ->with('tab_content',$tab_content);
    }

    /******************* FORMS DE DAILY CHECK **************************/

    public function createDailyCheck($id){

        $data = Equipo::findOrFail($id);
        
        if(!current_user()->can('see',$data)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }

        $formulario = Formulario::whereNombre('form_montacarga_daily_check')->first();

         $formulario_registro = FormularioRegistro::whereEquipoId($id)
             ->whereFormularioId($formulario->id)
             ->whereRaw('created_at >= CURDATE()')
             ->orderBy('id','DESC')
             ->first();
        

         if($formulario_registro){
            if($formulario_registro->turno_chequeo_diario>=4){
                $fecha=Carbon::parse($formulario_registro->created_at)->format('d/M/Y');
                request()->session()->flash('message.error','Ya ha completado los cuatro turnos para el dia '.$fecha);
                return redirect()->back();
            }
             $turno = $formulario_registro->turno_chequeo_diario+1;
         }else{
             $turno=1;
         }
         $supervisores =  User::whereHas('roles',function ($q){
            $q->where('role_id',3);
        })
        ->whereRaw("(crm_clientes_id ='$data->cliente_id' or crm_clientes_id like '%,$data->cliente_id%' or crm_clientes_id like '%$data->cliente_id,%')")
        ->get()->pluck('FullName','id');
      
      
        
        
        return view('frontend.equipos.create_daily_check')->with('data',$data)->with('formulario',$formulario)->with('turno',$turno)->with('supervisores',$supervisores);
    }

    public function editDailyCheck($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);
        if(!current_user()->can('see',$equipo)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }elseif(!current_user()->can('edit',$data)){
            request()->session()->flash('message.error','Este registro no esta disponible para ser modificado.');
            return redirect(route('equipos.detail',$equipo->id));
        }

        $formulario = Formulario::findOrFail($data->formulario_id);
        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');

        $datos=array();

        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
            }
        }

        return view('frontend.equipos.edit_daily_check')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('data',$data);
    }

    public function showDailyCheck($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);
        if(!current_user()->can('see',$equipo)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }

        $formulario = Formulario::findOrFail($data->formulario_id);
        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');

        $datos=array();

        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
            }
        }

        return view('frontend.equipos.show_daily_check')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('data',$data)
            ->with('datos',$datos);
    }

    public function storeDailyCheck(Request $request){


        $this->validate($request, [
            'supervisor_id'          => 'required'
        ]);

        try{
            $equipo_id = $request->equipo_id;
            $formulario_id = $request->formulario_id;
            $formulario = Formulario::find($formulario_id);
            $equipo = Equipo::find($equipo_id);
            $model = new FormularioRegistro();
            DB::transaction(function() use($model,$request,$formulario,$equipo){

                $model->formulario_id = $formulario->id;
                $model->creado_por = Sentinel::getUser()->id;
                $model->equipo_id = $request->equipo_id;
                $model->turno_chequeo_diario = $request->turno_chequeo_diario;
                $model->cliente_id = $equipo->cliente_id;
                $model->estatus = 'P';
                $model->equipo_status = 'O';
                $model->repuesto_status = 'L';
                $model->dia_semana = getDayOfWeek(date('N'));
                $model->semana = date('W');
                $model->ano = date('Y');

                if(!$model->save())
                {
                    Throw new \Exception('Hubo un problema y no se creo el registro!');
                }

            });

            // notificar a el o a los supervisores del cliente que tiene una firma pendiente por daily check


            $when = now()->addMinutes(1);
            
            $notis= User::whereRaw("crm_clientes_id ='$equipo->cliente_id' or crm_clientes_id like '%,$equipo->cliente_id%' or crm_clientes_id like '%$equipo->cliente_id,%' or notificar_siempre=1")->get() ;
            if(!empty($request->supervisor_id)){
                $notis = User::whereIn('id',[$request->supervisor_id])->get();
             }
            foreach ($notis as $u){
                if($u->isOnGroup('supervisorc') or $u->isOnGroup('supervisor-cliente') or  $u->isOnGroup('programador')  ){
                    notifica($u,(new NewReport($model,$u,$notis))->delay($when));
                    if(env_local()){
                        break;
                    }
                }
            }

            $request->session()->flash('message.success','Registro creado con éxito');
            $not_ok=false;
            foreach ($request->all() as $r){
                if($r=='M' or $r=='R'){
                    $not_ok=true;
                    break;
                }
            }
            if($not_ok and $model->status=='C'){
                 return redirect(route('equipos.create_tecnical_support_prefilled',[$equipo_id,$model->id]));
            }
            return redirect(route('equipos.detail',$equipo_id));

        }catch (\Exception $e){
            $request->session()->flash('message.error',$e->getMessage());
            return redirect(route('equipos.create_daily_check',$equipo_id));
        }


    }

    public function updateDailyCheck(Request $request)
    {
      
        try {
            $validate= [
                'equipo_id'              => 'required',
                'formulario_id'          => 'required',
                'formulario_registro_id' => 'required',
                'ok_supervisor'          => 'required',
            ];
            if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador')){
                unset($validate['ok_supervisor']);
            }
            $this->validate($request,$validate);

            $formulario_registro_id = $request->formulario_registro_id;
            
            $model = FormularioRegistro::findOrFail($formulario_registro_id);

          
            $model->updated_at =Carbon::now();
         
            $model->save();
          
    
            $request->session()->flash('message.success', 'Registro guardado con éxito');

            if($model->data()->wherein('valor',['M','R'])->count()>0){
                return redirect(route('equipos.create_tecnical_support_prefilled',[$model->equipo_id,$model->id]));
            }
            return redirect(route('equipos.detail', $model->equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_daily_check',$request->formulario_registro_id))->withInput($request->all());
        }
    }

    public function deleteRegistroForm($id,Request $request){
        $model = FormularioRegistro::findOrFail($id);
        $model->deleted_by=current_user()->id;
        $model->deleted_at = Carbon::now();
        if($model->save()){
            $request->session()->flash('message.success', 'Registro eliminado con éxito');
        }else{
            $request->session()->flash('message.error', 'Registro no se pudo eliminar');
        }
        return redirect(route('equipos.detail', $model->equipo_id));
 
    }
    /******************* FORMS DE MANTENIMIENTO PREVENTIVO **************************/

    public function createMantPrev($id,$tipo){

        $data = Equipo::findOrFail($id);
        $ver=current_user()->can('see',$data);

        if(!current_user()->can('see',$data)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }

        $forms = [1=>'form_montacarga_counter_rc',2=>'form_montacarga_combustion',3=>'form_montacarga_counter_fc',4=>'form_montacarga_counter_sc',
            5=>'form_montacarga_pallet',6=>'form_montacarga_reach',7=>'form_montacarga_stock_picker',8=>'form_montacarga_wave_stacker_walke',
            9=>'form_montacarga_wave_stacker_walke',10=>'form_montacarga_wave_stacker_walke'];

        if($data->sub_equipos_id==1){
            $formulario = Formulario::whereNombre('form_montacarga_combustion')->first();
        }else{
            $formulario = Formulario::whereNombre($forms[$tipo])->first();
        }

        return view('frontend.equipos.create_mant_prev')->with('data',$data)->with('formulario',$formulario);
    }

    public function editMantPrev($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);

        if(!current_user()->can('see',$equipo)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }
        $formulario = Formulario::findOrFail($data->formulario_id);

        return view('frontend.equipos.edit_mant_prev')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('data',$data);
    }

    public function showMantPrev($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);

        if(!current_user()->can('see',$equipo)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }
        $formulario = Formulario::findOrFail($data->formulario_id);

        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');

        $datos=array();
        $creador=array();
        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
               
                if($c->tipo=='firma'){
                    $data_firma=$data->data()->where('formulario_campo_id',$c->id)->first();
                    $creador[$c->nombre]=$data_firma->creador->full_name;
                }
            }
        } 
        return view('frontend.equipos.show_mant_prev')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('data',$data)
            ->with('creador',$creador)
            ->with('datos',$datos);
    }

    public function storeMantPrev(Request $request)
    {
        try {
            $equipo_id = $request->equipo_id;
            $formulario_id = $request->formulario_id;
            $formulario = Formulario::find($formulario_id);
            $equipo = Equipo::find($equipo_id);
            $model = new FormularioRegistro();


            DB::transaction(function () use ($model, $request, $formulario, $equipo) {

                $model->formulario_id = $formulario->id;
                $model->creado_por = Sentinel::getUser()->id;
                $model->equipo_id = $request->equipo_id;
                $model->cliente_id = $equipo->cliente_id;
                $model->estatus = 'P';
                $model->equipo_status = 'O';
                $model->repuesto_status = 'L';
                if (!$model->save()) {
                    throw new \Exception('Hubo un problema y no se creo el registro!');
                }else{
                  $model->createSolicitudMontacarga();
                }
            });

            //aqui hay que ver a quien notificar

            $request->session()->flash('message.success', 'Registro creado con éxito');
            return redirect(route('equipos.detail', $equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }

    public function updateMantPrev(Request $request)
    {
     
        try {
            $validate=  [
                'equipo_id'              => 'required',
                'formulario_id'          => 'required',
                'formulario_registro_id' => 'required',
                'trabajo_recibido_por'  => 'required',
            ];
            if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador')){
                unset($validate['trabajo_recibido_por']);
            }
            $this->validate($request,$validate);
            $formulario_registro_id = $request->formulario_registro_id;
            $model = FormularioRegistro::findOrFail($formulario_registro_id);
            $formulario = Formulario::findOrFail($model->formulario_id);
            $model->updated_at =Carbon::now();
            if($model->save()){
                $model->createSolicitudMontacarga();
                $request->session()->flash('message.success', 'Registro guardado con éxito');
            }else{
                $request->session()->flash('message.error', 'Hubo algun error y no se pudo actualizar');
            }

            return redirect(route('equipos.detail', $model->equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_mant_prev',$request->formulario_registro_id))->withInput($request->all());
        }
    }

    public function imprimirMantPrev($id){

        $formularioRegistro = FormularioRegistro::find($id);
        if($formularioRegistro->estatus<>'C'){
            request()->session()->flash('message.error','Este reporte debe estar cerrado para poder imprimirse, ya que debe generar una solicitud');
            return redirect()->back();
        }
        $pdf = $formularioRegistro->savePdf($formularioRegistro->solicitud(),false);
        return $pdf->Output('mantenimiento_preventivo.pdf', 'I');
    }
    /******************* FORM DE SOPORTE TECNICO **************************/

    public function createTecnicalSupport($id,$dailycheck=0){
        $detalleIni='';
        if($dailycheck>0){
             $dc=FormularioRegistro::findOrFail($dailycheck);
             if($dc){
                $data=FormularioData::where('formulario_registro_id',$dc->id)->whereTipo('radio')->where('valor','<>','OK')->get();
                foreach ($data as $d){
                $detalleIni.= $d->campo->etiqueta.' '.checkBoxDetail($d->valor).'\\ \r\n';

                }
             }
             //dd($detalleIni);
        }
        $data = Equipo::findOrFail($id);
        if(!current_user()->can('see',$data)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }
        $formulario = Formulario::whereNombre('form_montacarga_servicio_tecnico')->first();
        return view('frontend.equipos.create_tecnical_support_report')
                    ->with('data',$data)
                    ->with('formulario',$formulario)
                    ->with('detalle',$detalleIni);

    }

    public function editTecnicalSupport($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);
        $formulario = Formulario::whereNombre('form_montacarga_servicio_tecnico')->first();

        $campos = $formulario->campos()->whereIn('nombre',['hora_entrada','hora_salida','tecnico_asignado'])->pluck('id');
        $otrosDatos = $data->data()->whereIn('formulario_campo_id',$campos)->orderBy('formulario_campo_id','ASC')->pluck('valor');
      
        return view('frontend.equipos.edit_tecnical_support_report')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('otrosCampos',$otrosDatos)
            ->with('data',$data);
    }

    public function showTecnicalSupport($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);
        $formulario = Formulario::whereNombre('form_montacarga_servicio_tecnico')->first();

        $campos = $formulario->campos()->whereIn('nombre',['hora_entrada','hora_salida','tecnico_asignado'])->pluck('id');
        $otrosDatos = $data->data()->whereIn('formulario_campo_id',$campos)->orderBy('formulario_campo_id','ASC')->pluck('valor');
        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');

        $datos=array();

        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
            }
        }     
        return view('frontend.equipos.show_tecnical_support_report')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('otrosCampos',$otrosDatos)
            ->with('data',$data)
            ->with('datos',$datos);
    }

    public function storeTecnicalSupport(Request $request){
       
        $this->validate($request, [
            'equipo_id'  => 'required',
            'formulario_id'  => 'required',
            'fecha'          => 'required|date',
        ]);

        $equipo_id = $request->equipo_id;
        $formulario_id = $request->formulario_id;
        $tipo_equipos_id = $request->tipo_equipos_id;
        $formulario = Formulario::find($formulario_id);
        $model = new FormularioRegistro();
        $equipo = Equipo::findOrFail($equipo_id);
        $status='P';
        if($model->status=='C')
            $status='C';
        //DB::transaction(function() use($model,$request,$formulario,$equipo,$status){

            $model->formulario_id = $formulario->id;
            $model->creado_por = Sentinel::getUser()->id;
            $model->equipo_id = $request->equipo_id;
            $model->cliente_id = $request->cliente_id;
            $model->estatus = $status;
            $model->equipo_status = 'O';
            $model->repuesto_status = 'L';
            if(count($request->all())>=26 and \Sentinel::hasAccess('sp.parteB')){
                $model->tecnico_asignado=current_user()->id;
                $model->fecha_asignacion=\Carbon\Carbon::now();
                $status='A';
                $model->estatus = $status;
            }
            if($model->save())
            {
                
                $users = User::Join('role_users','users.id','role_users.user_id')
                ->Join('roles','role_users.role_id','roles.id')
                ->Join('activations','users.id','activations.user_id')
                ->whereRaw("(roles.slug='supervisorc' or roles.slug='supervisor-cliente')
                            AND activations.completed=1
                            AND (crm_clientes_id ='$equipo->cliente_id'  
                            OR crm_clientes_id LIKE '%$equipo->cliente_id,%' 
                            OR crm_clientes_id LIKE '%,$equipo->cliente_id%' 
                            OR  crm_clientes_id LIKE '%,$equipo->cliente_id,%'
                            OR  users.notificar_siempre=1
                            )")
                ->get();
                // crear notificacion al supervisor del cliente
                $when = now()->addMinutes(1);
                foreach($users as $user){
                    notifica($user,(new NewTecnicalSupport($model))->delay($when));
                    if(env('APP_ENV')=='local'){
                        break;
                    }   
                }
                    
            }else{
                Throw new \Exception('Hubo un problema y no se creo el registro!');
            }
        //});

        $request->session()->flash('message.success','Registro creado con exito');
        return redirect(route('equipos.detail',$equipo_id));
    }

    public function updateTecnicalSupport(Request $request)
    {
        ini_set('error_reporting', E_STRICT);
        
        //try {

            $this->validate($request, [
                'equipo_id'              => 'required',
                'formulario_id'          => 'required',
                'formulario_registro_id' => 'required',
               // 'trabajo_recibido_por'  => 'required',
            ]);

            $formulario_registro_id = $request->formulario_registro_id;
            $model = FormularioRegistro::findOrFail($formulario_registro_id);
            $datos=FormularioData::Join('formulario_campos','formulario_data.formulario_campo_id','formulario_campos.id')
                                    ->whereFormularioRegistroId($model->id)
                                    ->where('cambio_estatus',1)
                                    ->select('valor')
                                    ->first();
            if($datos){
                $datos=$datos->toArray();
            }                        
            if(isset($datos['valor']) and !empty($datos['valor'])){
                $model->estatus='C';
            }
            $model->updated_at =Carbon::now();
            $model->save();
            $request->session()->flash('message.success', 'Registro guardado con éxito');
            return redirect(route('equipos.detail', $model->equipo_id));

        /*} catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_tecnical_support',$request->formulario_registro_id))->withInput($request->all());
        }*/
    }

    public function assignSupervisorTS(Request $request){
        $this->validate($request, [
            'sup_formulario_registro_id'  => 'required',
            'redirect_to'  => 'required',
            'supervisor_id'  => 'required',
            'fecha'  => 'required',
        ]);
        $model = FormularioRegistro::findOrFail($request->sup_formulario_registro_id);
        $model->trabajado_por = $request->supervisor_id;
        $model->fecha_trabajo = $request->fecha;
        if($model->save()){
            $user=User::find($request->supervisor_id);
            $request->session()->flash('message.success', 'Se a asignado el supervisor '.$user->getFullName().' de forma exitosa.');
        }else{
            $request->session()->flash('message.error', 'Hubo un error y no se pudo guardar');
        }

        return redirect($request->redirect_to);
    }

    public function aprobar_cotizacion(Request $request){
        $this->validate($request, [
            'cot_formulario_registro_id'  => 'required',
            'redirect_to'  => 'required',
            'aprobada'  => 'required',
        ]);

        $model = FormularioRegistro::findOrFail($request->cot_formulario_registro_id);
        $model->cotizacion = $request->aprobada;
        $model->fecha_trabajo = $request->fecha;
        if($model->save()){
            FormularioRegistroEstatus::create([
                'formulario_registro_id'=>$model->id,
                'user_id'=>current_user()->id,
                'cotizacion'=>$request->aprobada,
            ]);
            $request->session()->flash('message.success', 'Se ha marcado el reporte #'.$request->cot_formulario_registro_id.'  como cotizacion aprobada.');
        }else{
            $request->session()->flash('message.error', 'Hubo un error y no se pudo guardar');
        }

        return redirect($request->redirect_to);
    }

    public function marcar_accidente(Request $request){
        $this->validate($request, [
            'acc_formulario_registro_id'  => 'required',
            'redirect_to'  => 'required',
            'accidente'  => 'required',
        ]);

        $model = FormularioRegistro::findOrFail($request->acc_formulario_registro_id);
        $model->accidente = $request->accidente;
        if($model->save()){
            FormularioRegistroEstatus::create([
                'formulario_registro_id'=>$model->id,
                'user_id'=>current_user()->id,
                'accidente'=>$request->accidente,
            ]);
            $request->session()->flash('message.success', 'Se ha marcado el accidente en reporte #'.$request->cot_formulario_registro_id.'  como cotizacion aprobada.');
        }else{
            $request->session()->flash('message.error', 'Hubo un error y no se pudo guardar');
        }

        return redirect($request->redirect_to);
    }

    public function assignTecnicalSupport(Request $request,$id){

        $this->validate($request, [
            'tecnico_asignado'  => 'required',
        ]);

        $model = FormularioRegistro::findOrFail($id);
        $model->estatus = 'A';
        $model->tecnico_asignado = $request->tecnico_asignado;
        $model->fecha_asignacion =Carbon::now();
        $user = User::findOrFail($request->tecnico_asignado);

        if($model->save()){
            // crear notificacion al tecnico asignado
            $when = now()->addMinutes(1);
            notifica($user,(new NewTecnicalSupportAssignTicket($model))->delay($when));
            $request->session()->flash('message.success', 'Se a asignado el servicio de soporte técnico a '.$user->getFullName().' de forma exitosa.');
        }else{
            $request->session()->flash('message.error', 'Se a asignado el servicio de soporte técnico de forma exitosa.');
        }

        return redirect(route('equipos.detail', $model->equipo_id));
    }

    public function startTecnicalSupport($id){

        $model = FormularioRegistro::findOrFail($id);
        $model->estatus = 'PR';

        if($model->save()){
            $horaEntredaCampo = $model->formulario()->first()->campos()->whereNombre('hora_entrada')->first();
            $data = $model->data()->whereFormularioCampoId($horaEntredaCampo->id)->first();
            $data->valor = date('H:i');
            $data->user_id = current_user()->id;
            $data->save();

            $htecnicoCampo = $model->formulario()->first()->campos()->whereNombre('tecnico_asignado')->first();
            $data = $model->data()->whereFormularioCampoId($htecnicoCampo->id)->first();
            $data->valor = current_user()->getFullName();
            $data->user_id = current_user()->id;
            $data->save();

            request()->session()->flash('message.success', 'Inicio de servicio tecnico exitoso , no olvide hacer cierre del mismo al terminar.');
            return redirect(route('equipos.detail', $model->equipo_id));
        }

    }

    /******************* REPORTES **************************/

    public function reportes($reporte,$id){
       $datos['cab']=FormularioRegistro::find($id);


       if($reporte=='form_montacarga_servicio_tecnico'){
            $datos['det']=getFormData($datos['cab']->formulario->nombre,0,0,$id);
            //dd($datos);
            $orientation = 'landscape';
            $customPaper = array(0,0,950,950);

            $pdf = PDF::loadView('frontend.equipos.reportes.form_montacarga_servicio_tecnico',compact('datos'))
                    ->setPaper($customPaper, $orientation); 
            return $pdf->stream('pdfview.pdf');
            return view('frontend.equipos.reportes.form_montacarga_servicio_tecnico')->with('datos',$datos);
       }
       if($reporte=='mantenimiento_preventivo'){
            $file= $datos['cab']->savePdf();

            return Response::make(file_get_contents($file), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; prevew.pdf',
            ]);
       }

       if($reporte=='form_montacarga_daily_check'){

        $file= $datos['cab']->savePdfDC();
        
        return Response::make(file_get_contents($file), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; prevew.pdf',
        ]);
       }
       if($reporte=='form_control_entrega_alquiler'){
        $datos['det']=getFormData($datos['cab']->formulario->nombre,0,0,$id);
        
        $orientation = 'portrait';
        $customPaper = array(0,0,950,1230);
       // dd($datos['cab']);
        $pdf = PDF::loadView('frontend.equipos.reportes.form_control_alquiler',compact('datos'))
                ->setPaper($customPaper, $orientation); 
        return $pdf->stream('pdfview.pdf');
        return view('frontend.equipos.reportes.form_control_alquiler')->with('datos',$datos);
   }
    }

    public function calendar(){

        $registros = FormularioRegistro::with('estatusHistory')
        ->whereHas('formulario',function ($q){
            $q->where('formularios.nombre','form_montacarga_servicio_tecnico');
        })
        //->where('estatus','C')
        ->get();

        $eventos=array();
        foreach ($registros as $r){

            $equipo = Equipo::find($r->equipo_id);
            if($equipo){
                $eventos[$r->id]['id'] = $r->id;
                $eventos[$r->id]['estatus'] = $r->estatus;
                $eventos[$r->id]['equipo'] = $equipo->numero_parte;
                $eventos[$r->id]['cliente'] = $equipo->cliente->nombre;
                $fec_ini='';
                $fec_fin='';
                foreach ($r->estatusHistory as $h){
                    $eventos[$r->id]['fechas'][$h->estatus]['fecha'] = Carbon::parse($h->created_at)->format('Y-m-d H:i');
                    $eventos[$r->id]['fechas'][$h->estatus]['usuario'] = $h->user->getFullName();
                    if($h->estatus =='P') $fec_ini =  Carbon::parse($h->created_at)->format('Y-m-d H:i');
                    $fec_fin =  Carbon::parse($h->created_at)->format('Y-m-d H:i');

                }
                $eventos[$r->id]['inicio'] =$fec_ini;
                $eventos[$r->id]['fin'] = $fec_fin;
            }
        }
        //dd($eventos);

        return view('frontend.equipos.calendar',compact('eventos'));
    }
    
    public function agregar_status(Request $request){
        
        $registros = FormularioRegistro::find($request->formulario_registro_id);      
        $index=1;
        if(!empty( $request->equipo_status)){
            $registros->equipo_status=$request->equipo_status;
            $registros->repuesto_status='L';
            if($request->equipo_status=='I')
                $registros->repuesto_status='E';
            $var=$request->equipo_status;
            $index=1;
        }   
        if(!empty( $request->repuesto_status)){
            $registros->repuesto_status=$request->repuesto_status;
            $registros->equipo_status='O';
            $var=$request->repuesto_status;
            $index=2;
        }
     
        if($registros->save()){
            $frs=FormularioRegistroEstatus::create([
                'formulario_registro_id'=>$registros->id,
                'user_id'=>current_user()->id,
                'equipo_status'=>$request->equipo_status,
                'repuesto_status'=>$request->repuesto_status,
            ]);
       
            request()->session()->flash('message.success','Se ha cambiado el estado del '.$request->tipo.' a '.getStatusHtml($var,$index).' correctamente');
        }else{
            request()->session()->flash('message.error','Hubo un error cambiando el status');
       
        }
       
        return redirect($request->redirect_to);
      
      
    }
    /****************CONTROL DE ENTREGAS*********************/
    function createControlEntrega($id){

     
        $data = Equipo::findOrFail($id);
        $formulario = Formulario::whereTipo('control_alquiler')->first();


        return view('frontend.equipos.create_control_alquiler') 
        ->with('formulario',$formulario)
        ->with('data',$data);
      
    }

    public function storeControlEntrega(Request $request){
      
        $this->validate($request, [
            'equipo_id'  => 'required'
        ]);

        $equipo_id = $request->equipo_id;
        $formulario_id = $request->formulario_id;
        $tipo_equipos_id = $request->tipo_equipos_id;
        $formulario = Formulario::find($formulario_id);
        $model = new FormularioRegistro();
        $equipo = Equipo::findOrFail($equipo_id);
        $status='P';
        if($model->status=='C')
            $status='C';
        //DB::transaction(function() use($model,$request,$formulario,$equipo,$status){

            $model->formulario_id = $formulario->id;
            $model->creado_por = Sentinel::getUser()->id;
            $model->equipo_id = $request->equipo_id;
            $model->cliente_id = $request->cliente_id;
            $model->estatus = $status;
            $model->equipo_status = 'O';
            $model->repuesto_status = 'L';
            
            if($model->save())
            {
                
              // mandar correo si hace falta
                    
            }else{
                Throw new \Exception('Hubo un problema y no se creo el registro!');
            }
        //});

        $request->session()->flash('message.success','Registro creado con exito');
        return redirect(route('equipos.detail',$equipo_id));
    }

    public function showControlEntrega($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);
        $formulario = Formulario::whereNombre('form_control_entrega_alquiler')->first();

        $campos = $formulario->campos()->whereIn('nombre',['hora_entrada','hora_salida','tecnico_asignado'])->pluck('id');
        $otrosDatos = $data->data()->whereIn('formulario_campo_id',$campos)->orderBy('formulario_campo_id','ASC')->pluck('valor');

        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');

        $datos=array();

        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
            }
        }     
        return view('frontend.equipos.show_control_alquiler')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('otrosCampos',$otrosDatos)
            ->with('data',$data)
            ->with('datos',$datos);
             
    }
    
    public function updateControlEntrega(Request $request)
    {
        ini_set('error_reporting', E_STRICT);
        

        
            $this->validate($request, [
                'equipo_id'              => 'required',
                'formulario_id'          => 'required',
                'formulario_registro_id' => 'required',
               // 'trabajo_recibido_por'  => 'required',
            ]);

            $formulario_registro_id = $request->formulario_registro_id;
            $model = FormularioRegistro::findOrFail($formulario_registro_id);
            $datos=FormularioData::Join('formulario_campos','formulario_data.formulario_campo_id','formulario_campos.id')
                                    ->whereFormularioRegistroId($model->id)
                                    ->where('cambio_estatus',1)
                                    ->select('valor')
                                    ->first();
            if($datos){
                $datos=$datos->toArray();
            }                        
            if(isset($datos['valor']) and !empty($datos['valor'])){
                $model->estatus='C';
            }
            $model->updated_at =Carbon::now();
            $model->save();
            $request->session()->flash('message.success', 'Registro guardado con éxito');
            return redirect(route('equipos.detail', $model->equipo_id));

      
    }

    public function editControlEntrega($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);
        $formulario = Formulario::whereNombre('form_control_entrega_alquiler')->first();

        $campos = $formulario->campos()->whereIn('nombre',['hora_entrada','hora_salida','tecnico_asignado'])->pluck('id');
        $otrosDatos = $data->data()->whereIn('formulario_campo_id',$campos)->orderBy('formulario_campo_id','ASC')->pluck('valor');
      
        return view('frontend.equipos.edit_control_alquiler')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('otrosCampos',$otrosDatos)
            ->with('data',$data);
    }
}
