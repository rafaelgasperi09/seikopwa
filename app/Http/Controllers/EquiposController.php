<?php

namespace App\Http\Controllers;
use App\Formulario;
use App\FormularioData;
use App\FormularioRegistro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\TipoEquipo;
use App\Equipo;
use App\SubEquipo;
use Illuminate\Support\Facades\DB;
use Sentinel;

class EquiposController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $subEquipos=SubEquipo::orderBy('id','desc')->get();
        $tipoEquipos=Equipo::select('equipos.sub_equipos_id','equipos.tipo_equipos_id','tipo_equipos.display_name')
                                ->join('tipo_equipos','equipos.tipo_equipos_id','=','tipo_equipos.id')
                                ->groupBy('equipos.sub_equipos_id','equipos.tipo_equipos_id')
                                ->get();

        $tipoEquiposArray=array();
        foreach($tipoEquipos as $t){
            $tipoEquiposArray[$t->sub_equipos_id][$t->tipo_equipos_id]=$t->display_name;
        }
        return view('frontend.equipos.index')->with('tipos',$tipoEquiposArray)->with('subEquipos',$subEquipos);

    }

    public function tipo($sub,$id){

        $datos=array('sub'=>$sub,
                     'tipo'=>$id,
                     'subName'=>getSubEquipo($sub,'name'),
                     'tipoName'=>getTipoEquipo($id));
        if($id=='todos')
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->paginate(10);
        else
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->where('tipo_equipos_id',$id)->paginate(10);


        return view('frontend.equipos.index')->with('equipos',$equipos)->with('datos',$datos);
    }

    public function search(Request $request,$sub,$id){
        if($id=='todos')
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->where('numero_parte','like',"%".$request->q."%")->paginate(10);
        else
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->where('tipo_equipos_id',$id)->where('numero_parte','like',"%".$request->q."%")->paginate(10);


        return view('frontend.equipos.page')->with('data',$equipos);
    }


    public function detail($id){

        $data = Equipo::findOrFail($id);
        return view('frontend.equipos.detail')->with('data',$data);
    }

    public function createDailyCheck($id){

        $data = Equipo::findOrFail($id);
        $formulario = Formulario::whereNombre('form_montacarga_daily_check')->first();

         $fr = FormularioRegistro::whereEquipoId($id)
             ->whereFormularioId($formulario->id)
             ->whereRaw('created_at >= CURDATE()')
             ->orderBy('id','DESC')
             ->first();

         if($fr){
             $turno = $fr->turno_chequeo_diario+1;
         }else{
             $turno=1;
         }

        return view('frontend.equipos.create_daily_check')->with('data',$data)->with('formulario',$formulario)->with('turno',$turno);
    }

    public function storeDailyCheck(Request $request){


        $this->validate($request, [
            'equipo_id'  => 'required',
            'formulario_id'  => 'required',
            'turno_chequeo_diario' => 'required'
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

                if($model->save())
                {
                    foreach ($formulario->campos()->get() as $campo)
                    {

                        $valor =  $request->get($campo->nombre);
                        if($campo->nombre == 'semana') $valor = Carbon::now()->startOfWeek()->format('d-m-Y');
                        if($campo->nombre == 'dia_semana') $valor = getDayOfWeek(date('N'));
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
            return redirect(route('equipos.detail',$equipo_id));

        }catch (\Exception $e){
            $request->session()->flash('message.error',$e->getMessage());
            return redirect(route('equipos.store_daily_check',$equipo_id));
        }


    }


    public function createMantPrev($id,$tipo){

        $data = Equipo::findOrFail($id);
        $forms = [1=>'form_montacarga_counter_rc',2=>'form_montacarga_combustion',3=>'form_montacarga_counter_fc',4=>'form_montacarga_counter_sc',5=>'form_montacarga_counter_pallet_pe',6=>'form_montacarga_counter_reach',7=>'form_montacarga_counter_stock_picker'];

        $formulario = Formulario::whereNombre($forms[$tipo])->first();

        return view('frontend.equipos.create_mant_prev')->with('data',$data)->with('formulario',$formulario);
    }

    public function storeMantPrev(Request $request)
    {

        $this->validate($request, [
            'equipo_id' => 'required',
            'formulario_id' => 'required',
            'horometro' => 'required'
        ]);

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

                if ($model->save()) {
                    foreach ($formulario->campos()->get() as $campo) {
                        $valor = $request->get($campo->nombre);
                        $api_descripcion = '';
                        $form_data = FormularioData::create([
                            'formulario_registro_id' => $model->id,
                            'formulario_campo_id' => $campo->id,
                            'valor' => $valor,
                            'tipo' => $campo->tipo,
                            'api_descripcion' => $api_descripcion,
                        ]);

                        if (!$form_data) {
                            throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
                        }
                    }
                } else {
                    throw new \Exception('Hubo un problema y no se creo el registro!');
                }
            });

            $request->session()->flash('message.success', 'Registro creado con exito');
            return redirect(route('equipos.detail', $equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.create_mant_prev', $equipo->id, $equipo->tipo_equipos_id));
        }
    }

    public function createTecnicalSupport($id){

        $data = Equipo::findOrFail($id);
        $formulario = Formulario::whereNombre('form_montacarga_servicio_tecnico')->first();
        return view('frontend.equipos.create_tecnical_support_report')->with('data',$data)->with('formulario',$formulario);

    }


}
