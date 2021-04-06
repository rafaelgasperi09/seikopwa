<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Formulario;
use App\FormularioData;
use App\FormularioRegistro;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BateriaController extends Controller
{
    public function index(){

        $data = Componente::whereTipoComponenteId(2)->paginate(10);
        return view('frontend.baterias.index',compact('data'));
    }

    public function page(){

        $data = Componente::whereTipoComponenteId(2)->paginate(10);
        return view('frontend.baterias.page',compact('data'));
    }

    public function search(Request $request){

        $data = Componente::whereTipoComponenteId(2)->where('id_componente','LIKE',"%".$request->q."%")->paginate(10);
        return view('frontend.baterias.page',compact('data'));
    }


    public function detail($id){

        $data = Componente::findOrFail($id);
        return view('frontend.baterias.detail')->with('data',$data);
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

            if($model->save())
            {
                foreach ($formulario->campos()->get() as $campo)
                {
                    $valor =  $request->get($campo->nombre);
                    $api_descripcion = '';
                    $form_data = FormularioData::create([
                        'formulario_registro_id' => $model->id,
                        'formulario_campo_id'=>$campo->id,
                        'valor' =>$valor,
                        'tipo' => $campo->tipo,
                        'api_descripcion'=>$api_descripcion,
                    ]);

                    if(!$form_data)
                    {
                        Throw new \Exception('Hubo un problema y no se guardar el campo :'.$campo->nombre);
                    }
                }
            }else{
                Throw new \Exception('Hubo un problema y no se creo el registro!');
            }
        });

        $request->session()->flash('message.success','Registro creado con exito');
        return redirect(route('baterias.detail',$componente_id));
    }
}
