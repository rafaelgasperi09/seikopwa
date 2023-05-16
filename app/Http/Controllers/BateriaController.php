<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Formulario;
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
        $tab=array('','');
        if(!empty($request->get('tab'))){
            if($request->tab==1)
                $tab[0]='active';
            if($request->tab==2)
                $tab[1]='active';
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
            celda_4_1,",",celda_4_2,",",celda_4_3,",",celda_4_4,",",celda_4_5,",",celda_4_6,",<br/>  ",
            celda_5_1,",",celda_5_2,",",celda_5_3,",",celda_5_4,",",celda_5_5,",",celda_5_6,",<br/>  ",
            celda_6_1,",",celda_6_2,",",celda_6_3,",",celda_6_4,",",celda_6_5,",",celda_6_6) as celdas')
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
            if($row->estatus=='P'){
                $accion.='<a href="'.route('baterias.serv_tec_edit',$row->formulario_registro_id).'" target="_blank" title="Ver">
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
}
