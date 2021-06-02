<?php

namespace App\Observers;

use App\Formulario;
use App\FormularioCampo;
use App\FormularioData;
use App\FormularioRegistro;
use App\FormularioRegistroEstatus;
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
        //dd($request->all());
        foreach ($formulario->campos()->get() as $campo) {
            $valor = '';
            if($request->has($campo->nombre))
              $valor = $request->get($campo->nombre);

            if($campo->nombre == 'semana') {$valor = Carbon::now()->startOfWeek()->format('d-m-Y');}
            if($campo->nombre == 'dia_semana') {$valor = getDayOfWeek(date('N'));}
            if($campo->tipo=='firma' && $request->get($campo->nombre)){
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
                    $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->equipo_id.'_'.time().'.'.$ext;
                    $destinationPath = storage_path('/app/public/equipos');
                    $img->resize(1200, 1200)->save($destinationPath.'/'.$filename);
                    $valor =  $filename;

                }
            }

            $form_data = FormularioData::create([
                'formulario_registro_id' => $formularioRegistro->id,
                'formulario_campo_id' => $campo->id,
                'valor' => $valor,
                'tipo' => $campo->tipo,
                'api_descripcion' => '',
                'user_id'=>current_user()->id
            ]);

            if (!$form_data) {
                throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
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

        if($formularioRegistro->isDirty('estatus')){
            FormularioRegistroEstatus::create([
                'formulario_registro_id'=>$formularioRegistro->id,
                'user_id'=>current_user()->id,
                'estatus'=>$formularioRegistro->estatus
            ]);
        }else{
            $formulario = Formulario::find($formularioRegistro->formulario_id);
            $request = request();
            DB::transaction(function () use ($request, $formulario,$formularioRegistro) {

                foreach ($formulario->campos()->get() as $campo) {

                    $valor = $request->get($campo->nombre);

                    if(!empty($valor)){

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
                                $filename = $formulario->tipo.'_'.$formularioRegistro->id.'_'.$formularioRegistro->equipo_id.'_'.time().'.'.$ext;
                                $destinationPath = storage_path('/app/public/equipos');
                                $img->resize(1200, 1200)->save($destinationPath.'/'.$filename);
                                $valor =  $filename;

                            }
                        }
                        if($campo->nombre == 'hora_salida'){
                            $valor = date('H:i');
                        }

                        $form_data = FormularioData::whereFormularioRegistroId($formularioRegistro->id)->whereFormularioCampoId($campo->id)->first();
                        $form_data->valor = $valor;
                        $form_data->user_id = current_user()->id;

                        if (!$form_data->save()) {
                            throw new \Exception('Hubo un problema y no se guardar el campo :' . $campo->nombre);
                        }else{
                            $formularioCampo = FormularioCampo::find($form_data->formulario_campo_id);
                            if($formularioCampo->cambio_estatus && isset($form_data->valor)) {
                                $formularioRegistro->estatus = 'C';
                                $formularioRegistro->save();
                            }
                        }
                    }
                }
            });
        }

    }

}
