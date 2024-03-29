<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Formulario;
use App\Equipo;
use App\Tecnico;
use App\FormularioData;
use App\FormularioCampo;
use App\FormularioRegistro;
use App\PatientAbsence;
use PDF;
use Carbon\Carbon;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BateriasExport;
use App\Exports\HidratacionExport;

class BateriaController extends Controller
{
    public function index(){

        $data = Componente::FiltroBodega()->whereTipoComponenteId(2)->paginate(10);
        return view('frontend.baterias.index',compact('data'));
    }

    public function page(){

        $data = Componente::FiltroBodega()->whereTipoComponenteId(2)->paginate(10);
        return view('frontend.baterias.page',compact('data'));
    }

    public function search(Request $request){

        $data = Componente::FiltroBodega()->whereTipoComponenteId(2)->where('id_componente','LIKE',"%".$request->q."%")->paginate(10);
        return view('frontend.baterias.page',compact('data'));
    }


    public function detail($id,Request $request){
        $tab=array('active','','');
        if(!empty($request->get('tab'))){
            if($request->tab==2)
               $tab=array('','active','');
            if($request->tab==3)
               $tab=array('','','active');
        }
        $data = Componente::findOrFail($id);
        $campos=FormularioCampo::where('formulario_id',12)->get()->pluck('etiqueta','nombre');
 
        return view('frontend.baterias.detail')
                    ->with('data',$data)
                    ->with('tab',$tab)
                 
                    ->with('campos',$campos);
    }

    public function registrarEntradaSalida($id){

        $data = Componente::findOrFail($id);
        $formulario = Formulario::whereNombre('form_bat_control_carga')->first();

        return view('frontend.baterias.register_in_and_out',compact('data','formulario'));
    }

    public function guardarEntredaSalida(Request $request){

        $this->validate($request, [
            'componente_id'  => 'required',
            'formulario_id'  => 'required',
            'accion'         => 'required',
            'fecha'          => 'required|date',
            'hora_entrada'   => 'required',
        ]);
        $componente_id = $request->componente_id;
        $formulario_id = $request->formulario_id;
        $formulario = Formulario::find($formulario_id);
        $model = new FormularioRegistro();

        DB::transaction(function() use($model,$request,$formulario){

            $model->formulario_id = $formulario->id;
            $model->creado_por = Sentinel::getUser()->id;
            $model->componente_id = $request->componente_id;
            $model->estatus = 'C';

            if(!$model->save())
            {
                Throw new \Exception('Hubo un problema y no se creo el registro!');
            }
        });

        $request->session()->flash('message.success','Registro creado con éxito');
        return redirect(route('baterias.detail',$componente_id));
    }

    public function registrarHidratacion($id){

        $data = Componente::findOrFail($id);
        $formulario = Formulario::whereNombre('form_hidratacion_baterias')->first();

        return view('frontend.baterias.create_hidratacion',compact('data','formulario'));
    }

    
    public function guardarHidratacion(Request $request){

        $this->validate($request, [
            'componente_id'  => 'required',
            'formulario_id'  => 'required',
            'fecha'          => 'required|date',
            'hora_entrada'   => 'required',
        ]);
        $componente_id = $request->componente_id;
        $formulario_id = $request->formulario_id;
        $formulario = Formulario::find($formulario_id);
        $model = new FormularioRegistro();

        DB::transaction(function() use($model,$request,$formulario){

            $model->formulario_id = $formulario->id;
            $model->creado_por = Sentinel::getUser()->id;
            $model->componente_id = $request->componente_id;
            $model->estatus = 'C';

            if(!$model->save())
            {
                Throw new \Exception('Hubo un problema y no se creo el registro!');
            }
        });

        $request->session()->flash('message.success','Registro creado con éxito');
        return redirect(route('baterias.detail',$componente_id));
    }

    public function ServicioTecnico($id){
        $data = Componente::findOrFail($id);
        $formulario = Formulario::whereNombre('form_bat_serv_tec')->first();
        
        return view('frontend.baterias.servicio_tecnico',compact('data','formulario'));
      
    }

    public function ServicioTecnicoShow($id,Request $request){
    
        $data = FormularioRegistro::findOrFail($id);

        $componente = Componente::findOrFail($data->componente_id);
        $formulario = Formulario::whereNombre('form_bat_serv_tec')->first();
        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');

        $datos=array();

        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
            }
        }

        return view('frontend.baterias.servicio_tecnico_show',compact('data','formulario','datos','componente'));
    }

    public function ServicioTecnicoEdit($id,Request $request){
 
        $data = FormularioRegistro::findOrFail($id);
       
        $componente = Componente::findOrFail($data->componente_id);
        $formulario = Formulario::whereNombre('form_bat_serv_tec')->first();
        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');

        $datos=array();

        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
            }
        }

        return view('frontend.baterias.servicio_tecnico_edit',compact('data','formulario','datos','componente'));
    }

    public function ServicioTecnicoStore($id,Request $request){
        $data = Componente::findOrFail($id);
        $formulario = Formulario::whereNombre('form_bat_serv_tec')->first();
        $model = new FormularioRegistro();
        DB::transaction(function() use($model,$request,$formulario){

            $model->formulario_id = $formulario->id;
            $model->creado_por = Sentinel::getUser()->id;
            $model->componente_id = $request->componente_id;
            $model->estatus = 'P';

            if(!$model->save())
            {
                Throw new \Exception('Hubo un problema y no se creo el registro!');
            }
        });

        $request->session()->flash('message.success','Registro creado con éxito');
        return redirect(route('baterias.detail',$id));

       
      
    }

    public function ServicioTecnicoUpdate($id,Request $request){
        try{
        $model = FormularioRegistro::findOrFail($id);
        $model->updated_at =Carbon::now();
        $model->save();
        $request->session()->flash('message.success', 'Registro guardado con éxito');
        return redirect(route('baterias.detail',$model->componente_id));

        } catch (\Exception $e) {
        $request->session()->flash('message.error', $e->getMessage());
        return redirect(route('baterias.detail',$model->componente_id))->withInput($request->all());
        }    
      
    }

    public function datatable($id){

        $data = DB::table('form_carga_bateria_view')
            ->where('componente_id',$id)
            ->get();

        return DataTables::of($data)->make(true);
    }

    public function datatable_serv_tecnico($id){

       $data = DB::table('form_serv_tec_bat_view')
            ->selectRaw('*,concat(celda_1_1,",",celda_1_2,",",celda_1_3,",",celda_1_4,",",celda_1_5,",",celda_1_6,",<br/>  ",
            celda_2_1,",",celda_2_2,",",celda_2_3,",",celda_2_4,",",celda_2_5,",",celda_2_6,",<br/>  ",
            celda_3_1,",",celda_3_2,",",celda_3_3,",",celda_3_4,",",celda_3_5,",",celda_3_6,",<br/>  ",
            celda_4_1,",",celda_4_2,",",celda_4_3,",",celda_4_4,",",celda_4_5,",",celda_4_6) as celdas')
            ->where('componente_id',$id)
            ->orderBy('created_at','desc')
            ->get();

        return DataTables::of($data)
        ->addColumn('accion', function($row){
            $accion='<a href="'.route('baterias.serv_tec_show',$row->formulario_registro_id).'" target="_blank" title="Ver">
                        <span class="iconedbox bg-success">
                        <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
                        </span>
                    </a>';
            $accion.='<a href="'.route('baterias.download_st',$row->formulario_registro_id).'" target="_blank" title="Descargar">
                        <span class="iconedbox bg-warning">
                        <ion-icon name="download-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
                        </span>
                    </a>';
            if(Sentinel::getUser()->hasAccess('baterias.delete_reportes')){
                $accion.='<a href="'.route('baterias.delete_reportes',$row->formulario_registro_id).'" title="Borrar">
                <span class="iconedbox bg-danger">
                <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
                </span>
                </a>';
            }

            
            if($row->estatus=='P' and Sentinel::getUser()->hasAccess('baterias.serv_tec_update')){
                $accion.='<a href="'.route('baterias.serv_tec_edit',$row->formulario_registro_id).'" target="_blank" title="Firmar">
                <span class="iconedbox bg-primary">
                <ion-icon name="create-outline" role="img" class="md hydrated" aria-label="Editar"></ion-icon>
                </span>
                </a>';
            }
            
            return $accion;
        })
        ->editColumn('comentarios', function($row){
            return '<span title="'.$row->comentarios.'">'.substr($row->comentarios,0,30).'</span>...';
        })
        ->rawColumns(['accion','comentarios','celdas'])
        ->make(true);
    }

    public function datatable_hidratacion($id){

        $data = DB::table('form_hidratacion_bateria_view')
            ->leftJoin('tecnicos','form_hidratacion_bateria_view.tecnico_id','tecnicos.id')
            ->where('componente_id',$id)
            ->get();
            
        return DataTables::of($data)
        ->editColumn('tecnico_id', function($row){
            return $row->fullname;
        })
        ->addColumn('accion', function($row){
            $accion='<a href="'.route('baterias.show_hidratacion',$row->formulario_registro_id).'" target="_blank" title="Ver">
                        <span class="iconedbox bg-success">
                        <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
                        </span>
                    </a>';
            $accion.='<a href="'.route('baterias.edit_hidratacion',$row->formulario_registro_id).'" target="_blank" title="Editar">
                        <span class="iconedbox bg-primary">
                        <ion-icon name="create-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
                        </span>
                    </a>';
            if(Sentinel::getUser()->hasAccess('baterias.delete_reportes')){
                $accion.='<a href="'.route('baterias.delete_reportes',$row->formulario_registro_id).'" title="Borrar">
                <span class="iconedbox bg-danger">
                <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
                </span>
                </a>';
            }

           
            return $accion;
        })
        ->rawColumns(['accion'])
        ->make(true);
    }

    public function download($id){
        $format='PDF';
        if(request()->get('format')=='excel' ){
            $format=request()->get('format');
        }
        $bateria = Componente::findOrFail($id);
        $data = DB::table('form_carga_bateria_view')
            ->where('componente_id',$id)
            ->orderBy('fecha','DESC')
            ->get()->take(100);
        if($format=='PDF'){
            $pdf = PDF::loadView('frontend.baterias.pdf',compact('bateria','data'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream('historial_cargas.pdf');
        }else{
            return Excel::download(new BateriasExport($id), 'Equipo.xlsx');
        }

    }

    public function download_st($id){
        //dd($id);
        $format='PDF';
        if(request()->get('format')=='excel' ){
            $format=request()->get('format');
        }
        
        $data = DB::table('form_serv_tec_bat_view')
            ->where('formulario_registro_id',$id)
            ->first();
        
       //dd($data);
        $bateria = Componente::findOrFail($data->componente_id);
        //return view('frontend.baterias.pdf2')->with('data',$data)->with('bateria',$bateria);
        if($format=='PDF'){
            $pdf = PDF::loadView('frontend.baterias.pdf2',compact('bateria','data'));
            $pdf->setPaper('legal', 'portrait');
            return $pdf->stream('bateria_servicio_tecnico.pdf');
        }else{
            return Excel::download(new BateriasExport($id), 'Equipo.xlsx');
        }

    }

    public function download_hidratacion($id){
        $format='PDF';
        if(request()->get('format')=='excel' ){
            $format=request()->get('format');
        }
        $bateria = Componente::findOrFail($id);
        $data = DB::table('form_hidratacion_bateria_view')
            ->where('componente_id',$id)
            ->orderBy('fecha','DESC')
            ->get()->toArray();
        $datos=array_chunk($data, 15);
       $tecnicos=Tecnico::get()->pluck('fullname','id');

       //return view('frontend.baterias.hidratacion_excel',compact('bateria','data'));
        if($format=='PDF'){
            $pdf = PDF::loadView('frontend.baterias.pdf3',compact('bateria','datos','tecnicos'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream('historial_cargas.pdf');
        }else{
            return Excel::download(new HidratacionExport($id), 'hidratacion.xlsx');
        }

    }

    public function deleteRegistroForm($id,Request $request){
        $model = FormularioRegistro::findOrFail($id);
        $id=$model->equipo_id;
        $model->deleted_by=current_user()->id;
        $model->deleted_at = Carbon::now();
        if($model->save()){
            $request->session()->flash('message.success', 'Registro eliminado con éxito');
        }else{
            $request->session()->flash('message.error', 'Registro no se pudo eliminar');
        }
        return redirect()->back();
 
    }
   
    public function show_hidratacion($id,Request $request){
        
        $data = FormularioRegistro::findOrFail($id);
        $componente = Componente::findOrFail($data->componente_id);
        
        $formulario = Formulario::whereNombre('form_hidratacion_baterias')->first();
        $datos=array();
        $formularioData =$data->data()->get()->pluck('valor','formulario_campo_id');
        foreach($formulario->campos as $c){
            if(isset($formularioData[$c->id])){
                $datos[$c->nombre]=$formularioData[$c->id];
            }
        }     
        //dd($data->cliente());
        //$data = Equipo::find($datos->equipo_id);
       
        return view('frontend.baterias.hidratacion_show',compact('data','datos','componente','formulario'));
 
    }

    public function edit_hidratacion($id,Request $request){
        $data = FormularioRegistro::findOrFail($id);
        $componente = Componente::findOrFail($data->componente_id);
        $formulario = Formulario::whereNombre('form_hidratacion_baterias')->first();
        $tecnicos=Tecnico::get()->pluck('fullname','id');
        $tecnico=$data->data()->where('formulario_campo_id',1082)->first();
        if($tecnico)
            $tecnico=$tecnico->valor;

        return view('frontend.baterias.hidratacion_edit',compact('data','componente','formulario','tecnicos','tecnico'));
 
    }

    public function updateHidratacion(Request $request){
        $model = FormularioRegistro::findOrFail($request->id);
        $model->updated_at =Carbon::now();
        if($model->save()){
            $request->session()->flash('message.success', 'Registro editado con éxito');
        }else{
            $request->session()->flash('message.error', 'Registro no se pudo editado');
        }
        return redirect(route('baterias.detail',$model->componente_id));
    }
}
