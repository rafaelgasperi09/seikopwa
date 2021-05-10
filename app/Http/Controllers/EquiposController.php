<?php

namespace App\Http\Controllers;
use App\Formulario;
use App\FormularioData;
use App\FormularioRegistro;
use App\Http\Requests\SaveFormEquipoRequest;
use App\MontacargaConsecutivo;
use App\MontacargaCopiaSolicitud;
use App\MontacargaImagen;
use App\MontacargaSolicitud;
use App\Notifications\NewDailyCheck;
use App\Notifications\NewTecnicalSupportAssignTicket;
use App\User;
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
use Intervention\Image\Facades\Image;
use Sentinel;
use Illuminate\Support\Facades\Storage;
use PDF;
use Response;
class EquiposController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $subEquipos=SubEquipo::orderBy('id','desc')->get();

        $tipoEquipos = Equipo::select('equipos.sub_equipos_id','equipos.tipo_equipos_id','tipo_equipos.display_name')
                         ->FiltroCliente()
                         ->join('tipo_equipos','equipos.tipo_equipos_id','=','tipo_equipos.id')
                         ->groupBy('equipos.sub_equipos_id','equipos.tipo_equipos_id')
                         //->where('equipos.tipo_equipos_id','<=',7)
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
            $equipos=Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo($sub))->paginate(10);
        else
            $equipos=Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo($sub))->where('tipo_equipos_id',$id)->paginate(10);

        return view('frontend.equipos.index')->with('equipos',$equipos)->with('datos',$datos);
    }

    public function search(Request $request,$sub,$id){
        if($id=='todos')
            $equipos=Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo($sub))->where('numero_parte','like',"%".$request->q."%")->paginate(10);
        else
            $equipos=Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo($sub))->where('tipo_equipos_id',$id)->where('numero_parte','like',"%".$request->q."%")->paginate(10);


        return view('frontend.equipos.page')->with('data',$equipos);
    }

    public function detail($id){
        $data = Equipo::findOrFail($id);

        if(!current_user()->can('see',$data)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }

        $form['dc'] =FormularioRegistro::selectRaw("formulario_registro.semana,formulario_registro.ano,max(formulario_registro.id) as id,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Lunes1' THEN formulario_registro.id ELSE '' END) AS Lunes1,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Lunes2' THEN formulario_registro.id ELSE '' END) AS Lunes2,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Martes1' THEN formulario_registro.id ELSE '' END) AS Martes1,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Martes2' THEN formulario_registro.id ELSE '' END) AS Martes2,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Miercoles1' THEN formulario_registro.id ELSE '' END) AS Miercoles1,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Miercoles2' THEN formulario_registro.id ELSE '' END) AS Miercoles2,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Jueves1' THEN formulario_registro.id ELSE '' END) AS Jueves1,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Jueves2' THEN formulario_registro.id ELSE '' END) AS Jueves2,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Viernes1' THEN formulario_registro.id ELSE '' END) AS Viernes1,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Viernes2' THEN formulario_registro.id ELSE '' END) AS Viernes2,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Sabado1' THEN formulario_registro.id ELSE '' END) AS Sabado1,
                                        MAX(CASE CONCAT(formulario_registro.dia_semana,formulario_registro.`turno_chequeo_diario`) WHEN 'Sabado2' THEN formulario_registro.id ELSE '' END) AS Sabado2")
                                        ->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->where('equipo_id',$id)
                                        ->where('formularios.nombre','form_montacarga_daily_check')
                                        ->groupBy('formulario_registro.semana','formulario_registro.ano')
                                        ->get();

        $form['st']=FormularioRegistro::selectRaw('formulario_registro.*')->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->where('equipo_id',$id)->where('formularios.nombre','form_montacarga_servicio_tecnico')->get();


        $form['mp']=FormularioRegistro::selectRaw('formulario_registro.*')->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->where('equipo_id',$id)->where('formularios.nombre_menu','like',$data->tipo->name)->get();


        return view('frontend.equipos.detail')->with('data',$data)->with('form',$form);
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
             $turno = $formulario_registro->turno_chequeo_diario+1;
         }else{
             $turno=1;
         }


        return view('frontend.equipos.create_daily_check')->with('data',$data)->with('formulario',$formulario)->with('turno',$turno);
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

        return view('frontend.equipos.edit_daily_check')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('data',$data);
    }

    public function storeDailyCheck(Request $request){

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
                $model->dia_semana = getDayOfWeek(date('N'));
                $model->semana = date('W');
                $model->ano = date('Y');

                if($model->save())
                {
                    foreach ($formulario->campos()->get() as $campo)
                    {

                        $valor =  $request->get($campo->nombre);
                        if($campo->nombre == 'semana') $valor = Carbon::now()->startOfWeek()->format('d-m-Y');
                        if($campo->nombre == 'dia_semana') $valor = getDayOfWeek(date('N'));
                        if($request->get($campo->nombre)){
                            if($campo->tipo=='firma'){
                                $filename = Sentinel::getUser()->id.'_'.$campo->nombre.'_'.time().'.png';
                                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                                Storage::put('public/firmas/'.$filename,$data);
                                $valor =  $filename;
                            }
                            if(in_array($campo->tipo,['camera','file'])){
                                $file = $request->file($campo->nombre);
                                if($file){
                                    $img = Image::make($file->path());
                                    $ext = $file->getClientOriginalExtension();
                                    $filename = $model->id.'_'.$model->equipo_id.'_'.time().'.'.$ext;
                                    $destinationPath = storage_path('/app/public/equipos');
                                    $img->resize(1200, 1200)->save($destinationPath.'/'.$filename);
                                    $valor =  $filename;

                                }
                            }
                        }

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

            // notificar a el o a los supervisores del cliente que tiene una firma pendiente por daily check


            $when = now()->addMinutes(1);
            /*foreach (User::whereCrmClienteId(current_user()->crm_cliente->id)->get() as $u){
                if($u->isOnGroup('SupervisorC')){
                    $u->notify(new NewDailyCheck($model))->delay($when);
                }
            }*/

            $u = new User(['id'=>1,'email'=>'rafaelgasperi@clic.com.pa']);
            $u->notify((new NewDailyCheck($model))->delay($when));

            $request->session()->flash('message.success','Registro creado con éxito');
            return redirect(route('equipos.detail',$equipo_id));

        }catch (\Exception $e){
            $request->session()->flash('message.error',$e->getMessage());
            return redirect(route('equipos.create_daily_check',$equipo_id));
        }


    }

    public function updateDailyCheck(Request $request)
    {
        try {

            $this->validate($request, [
                'equipo_id'              => 'required',
                'formulario_id'          => 'required',
                'formulario_registro_id' => 'required',
                'ok_supervidor'  => 'required',
            ]);

            $formulario_registro_id = $request->formulario_registro_id;
            $formulario_id = $request->formulario_id;
            $formulario = Formulario::findOrFail($formulario_id);
            $model = FormularioRegistro::findOrFail($formulario_registro_id);
            $equipo = Equipo::findOrFail($model->equipo_id);

            DB::transaction(function () use ($model, $request, $formulario, $equipo) {

                foreach ($formulario->campos()->get() as $campo) {

                    $valor = $request->get($campo->nombre);

                    if(!empty($valor)){

                        if($campo->tipo=='firma'){

                            $filename = Sentinel::getUser()->id.'_'.$campo->nombre.'_'.time().'.png';
                            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                            Storage::put('public/firmas/'.$filename,$data);
                            $valor =  $filename;

                        }
                        $form_data = FormularioData::whereFormularioRegistroId($model->id)->whereFormularioCampoId($campo->id)->first();
                        $form_data->valor = $valor;
                        $form_data->user_id = current_user()->id;

                        if (!$form_data->save()) {
                            throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
                        }
                    }

                }

            });

            $request->session()->flash('message.success', 'Registro guardado con éxito');
            return redirect(route('equipos.detail', $equipo->id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_daily_check',$formulario_registro_id))->withInput($request->all());
        }
    }

    /******************* FORMS DE MANTENIMIENTO PREVENTIVO **************************/

    public function createMantPrev($id,$tipo){

        $data = Equipo::findOrFail($id);
        if(!current_user()->can('see',$data)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }
        $forms = [1=>'form_montacarga_counter_rc',2=>'form_montacarga_combustion',3=>'form_montacarga_counter_fc',4=>'form_montacarga_counter_sc',
            5=>'form_montacarga_pallet',6=>'form_montacarga_reach',7=>'form_montacarga_stock_picker',8=>'form_montacarga_wave_stacker_walke',
            9=>'form_montacarga_wave_stacker_walke',10=>'form_montacarga_wave_stacker_walke'];

        $formulario = Formulario::whereNombre($forms[$tipo])->first();

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

    public function storeMantPrev(SaveFormEquipoRequest $request)
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
                if ($model->save()) {
                    foreach ($formulario->campos()->get() as $campo) {
                        $valor = $request->get($campo->nombre);
                        $api_descripcion = '';
                        if($campo->tipo=='firma'){
                            $filename = Sentinel::getUser()->id.'_'.$campo->nombre.'_'.time().'.png';
                            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                            Storage::put('public/firmas/'.$filename,$data);
                            $valor =  $filename;
                        }

                        $form_data = FormularioData::create([
                            'formulario_registro_id' => $model->id,
                            'formulario_campo_id' => $campo->id,
                            'valor' => $valor,
                            'tipo' => $campo->tipo,
                            'api_descripcion' => $api_descripcion,
                            'user_id'=>current_user()->id
                        ]);

                        if (!$form_data) {
                            throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
                        }
                    }

                } else {
                    throw new \Exception('Hubo un problema y no se creo el registro!');
                }
            });

            //aqui hay que ver a quien notificar

            $request->session()->flash('message.success', 'Registro creado con éxito');
            return redirect(route('equipos.detail', $equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.create_mant_prev', ['id'=>$equipo->id, 'tipo'=>$equipo->tipo_equipos_id]))->withInput($request->all());
        }
    }

    public function updateMantPrev(Request $request)
    {
        try {

            $this->validate($request, [
                'equipo_id'              => 'required',
                'formulario_id'          => 'required',
                'formulario_registro_id' => 'required',
                'trabajo_recibido_por'  => 'required',
            ]);

            $formulario_registro_id = $request->formulario_registro_id;
            $equipo_id = $request->equipo_id;
            $formulario_id = $request->formulario_id;
            $formulario = Formulario::find($formulario_id);
            $equipo = Equipo::find($equipo_id);
            $model = FormularioRegistro::findOrFail($formulario_registro_id);

            DB::transaction(function () use ($model, $request, $formulario, $equipo) {

                foreach ($formulario->campos()->get() as $campo) {

                    $valor = $request->get($campo->nombre);

                    if(!empty($valor)){

                        if($campo->tipo=='firma'){

                            $filename = Sentinel::getUser()->id.'_'.$campo->nombre.'_'.time().'.png';
                            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                            Storage::put('public/firmas/'.$filename,$data);
                            $valor =  $filename;
                        }
                        $form_data = FormularioData::whereFormularioRegistroId($model->id)->whereFormularioCampoId($campo->id)->first();
                        $form_data->valor = $valor;
                        $form_data->user_id = current_user()->id;

                        if (!$form_data->save()) {
                            throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
                        }
                    }

                }

            });

            //aqui hay que ver a quien notificar

            $request->session()->flash('message.success', 'Registro creado con éxito');
            return redirect(route('equipos.detail', $equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_mant_prev',$formulario_registro_id))->withInput($request->all());
        }
    }

    /******************* FORM DE SOPORTE TECNICO **************************/

    public function createTecnicalSupport($id){

        $data = Equipo::findOrFail($id);
        if(!current_user()->can('see',$data)){
            request()->session()->flash('message.error','Su usuario no tiene permiso para realizar esta accion.');
            return redirect(route('equipos.index'));
        }
        $formulario = Formulario::whereNombre('form_montacarga_servicio_tecnico')->first();
        return view('frontend.equipos.create_tecnical_support_report')->with('data',$data)->with('formulario',$formulario);

    }

    public function editTecnicalSupport($id){

        $data = FormularioRegistro::findOrFail($id);
        $equipo = Equipo::findOrFail($data->equipo_id);
        $formulario = Formulario::whereNombre('form_montacarga_servicio_tecnico')->first();

        $campos = $formulario->campos()->whereIn('nombre',['hora_entrada','hora_salida','tecnico_asignado'])->pluck('id');
        $otrosDatos = $data->data()->whereIn('formulario_campo_id',$campos)->pluck('valor');

        return view('frontend.equipos.edit_tecnical_support_report')
            ->with('equipo',$equipo)
            ->with('formulario',$formulario)
            ->with('otrosCampos',$otrosDatos)
            ->with('data',$data);
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

        DB::transaction(function() use($model,$request,$formulario){

            $model->formulario_id = $formulario->id;
            $model->creado_por = Sentinel::getUser()->id;
            $model->equipo_id = $request->equipo_id;
            $model->cliente_id = $request->cliente_id;
            $model->estatus = 'P';

            if($model->save())
            {
                foreach ($formulario->campos()->get() as $campo)
                {
                    $valor =  $request->get($campo->nombre);
                    if($campo->tipo=='firma' && !empty($valor)){
                        $filename = Sentinel::getUser()->id.'_'.$campo->nombre.'_'.time().'.png';
                        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                        Storage::put('public/firmas/'.$filename,$data);
                        $valor =  $filename;
                    }

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
    }

    public function updateTecnicalSupport(Request $request)
    {
        try {

            $this->validate($request, [
                'equipo_id'              => 'required',
                'formulario_id'          => 'required',
                'formulario_registro_id' => 'required',
               // 'trabajo_recibido_por'  => 'required',
            ]);

            $formulario_registro_id = $request->formulario_registro_id;
            $equipo_id = $request->equipo_id;
            $formulario_id = $request->formulario_id;
            $formulario = Formulario::find($formulario_id);
            $equipo = Equipo::find($equipo_id);
            $model = FormularioRegistro::findOrFail($formulario_registro_id);

            DB::transaction(function () use ($model, $request, $formulario, $equipo) {

                foreach ($formulario->campos()->get() as $campo) {

                    $valor = $request->get($campo->nombre);

                    if(!empty($valor) or $campo->nombre == 'hora_salida'){

                        if($campo->tipo=='firma'){

                            $filename = Sentinel::getUser()->id.'_'.$campo->nombre.'_'.time().'.png';
                            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',  $valor ));
                            Storage::put('public/firmas/'.$filename,$data);
                            $valor =  $filename;
                        }
                        if($campo->nombre == 'hora_salida'){
                            $valor = date('H:i');
                        }
                        $form_data = FormularioData::whereFormularioRegistroId($model->id)->whereFormularioCampoId($campo->id)->first();
                        $form_data->valor = $valor;
                        $form_data->user_id = current_user()->id;

                        if (!$form_data->save()) {
                            throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
                        }
                    }

                }

            });

            //aqui hay que ver a quien notificar

            $request->session()->flash('message.success', 'Registro creado con éxito');
            return redirect(route('equipos.detail', $equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_tecnical_support',$formulario_registro_id))->withInput($request->all());
        }
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
            $user->notify(new NewTecnicalSupportAssignTicket($model));
            $request->session()->flash('message.success', 'Se a asignado el servicio de suporte técnico a '.$user->getFullName().' de forma exitosa.');
        }else{
            $request->session()->flash('message.error', 'Se a asignado el servicio de suporte tecnico de forma exitosa.');
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
            $pdf = PDF::loadView('frontend.equipos.reportes.form_montacarga_servicio_tecnico',compact('datos'));
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
    }
}
