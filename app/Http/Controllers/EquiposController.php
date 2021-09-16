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

        if($id=='todos'){
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
                                        ->where('equipo_id',$id)->where('formularios.tipo','serv_tec')->get();


        $form['mp']=FormularioRegistro::selectRaw('formulario_registro.*')->join('formularios','formulario_registro.formulario_id','=','formularios.id')
                                        ->where('equipo_id',$id)->where('formularios.tipo','mant_prev')->get();


        //dd($data->tipo->name);
        $mostrar=array('det'=>'show','reg'=>'');
        if($request->get('show')=='rows'){
            $mostrar['det']='';
            $mostrar['reg']='show';
        }

        $tab=array('t1'=>'active','t2'=>'','t3'=>'');
        $tab_content=array('t1'=>'active show','t2'=>'','t3'=>'');
        if(!\Sentinel::hasAnyAccess(['equipos.see_daily_check','equipos.edit_daily_check'])){
            $tab['t1']=''; $tab['t2']='active';
            $tab_content['t1']=''; $tab['t2']='active show';
        }

        if($request->get('tab')== 1){
            $tab=array('t1'=>'active','t2'=>'','t3'=>'');
            $tab_content=array('t1'=>'active show','t2'=>'','t3'=>'');
        }

        if($request->get('tab')== 2){
            $tab=array('t1'=>'','t2'=>'active','t3'=>'');
            $tab_content=array('t1'=>'','t2'=>'active show','t3'=>'');
        }

        if($request->get('tab')== 3){
            $tab=array('t1'=>'','t2'=>'','t3'=>'active');
            $tab_content=array('t1'=>'','t2'=>'','t3'=>'active show');
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
             $turno = $formulario_registro->turno_chequeo_diario+1;
         }else{
             $turno=1;
         }

        $supervisores = User::whereHas('roles',function ($q){
             $q->where('role_id',3);
         })->where('crm_cliente_id',$data->cliente_id)
         ->get()
        ->pluck('FullName','id');

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
            $datos[$c->nombre]=$formularioData[$c->id];
        }

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

                if(!$model->save())
                {
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
            //$u->notify((new NewDailyCheck($model))->delay($when));

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
                'ok_supervidor'          => 'required',
            ]);

            $formulario_registro_id = $request->formulario_registro_id;
            $model = FormularioRegistro::findOrFail($formulario_registro_id);
            $model->updated_at =Carbon::now();
            $model->save();
            $request->session()->flash('message.success', 'Registro guardado con éxito');
            return redirect(route('equipos.detail', $model->equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_daily_check',$request->formulario_registro_id))->withInput($request->all());
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
        $pdf = $formularioRegistro->savePdf($formularioRegistro->solicitud(),false);
        return $pdf->Output('mantenimiento_preventivo.pdf', 'I');
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

            if(!$model->save())
            {
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
            $model = FormularioRegistro::findOrFail($formulario_registro_id);
            $model->updated_at =Carbon::now();
            $model->save();
            $request->session()->flash('message.success', 'Registro guardado con éxito');
            return redirect(route('equipos.detail', $model->equipo_id));

        } catch (\Exception $e) {
            $request->session()->flash('message.error', $e->getMessage());
            return redirect(route('equipos.edit_tecnical_support',$request->formulario_registro_id))->withInput($request->all());
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
        //dd($eventos);

        return view('frontend.equipos.calendar',compact('eventos'));
    }
}
