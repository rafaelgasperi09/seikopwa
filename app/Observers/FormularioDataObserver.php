<?php

namespace App\Observers;

use App\Equipo;
use App\Formulario;
use App\FormularioCampo;
use App\FormularioData;
use App\FormularioRegistro;
use App\MontacargaConsecutivo;
use App\MontacargaCopiaSolicitud;
use App\MontacargaImagen;
use App\MontacargaSolicitud;

class FormularioDataObserver
{
    /**
     * Handle the formulario data "updated" event.
     *
     * @param  \App\FormularioData  $formularioData
     * @return void
     */
    public function updated(FormularioData $formularioData)
    {
        $formularioCampo = FormularioCampo::find($formularioData->formulario_campo_id);

        if($formularioCampo->cambio_estatus) { // cambio el estatus a C
            $formularioRegistro = FormularioRegistro::find($formularioData->formulario_registro_id);
            $formularioRegistro->estatus = 'C';

            if($formularioRegistro->save()){

                $formulario = Formulario::find($formularioRegistro->formulario_id);
                // solo si firmulario es de mantenimiento preventivo
                if(in_array($formulario->nombre,['form_montacarga_combustion','form_montacarga_counter_fc','form_montacarga_counter_rc','form_montacarga_counter_sc',
                    'form_montacarga_pallet','form_montacarga_reach','form_montacarga_stock_picker'])){

                    $horometroCampo = $formularioRegistro->formulario()->first()->campos()->where('nombre','horometro')->first();
                    $horometro =$formularioRegistro->data()->whereFormularioCampoId($horometroCampo->id)->first()->valor;

                    $obsCampo = $formularioRegistro->formulario()->first()->campos()->where('nombre','observacion')->first();
                    $obs =$formularioRegistro->data()->whereFormularioCampoId($obsCampo->id)->first()->valor;

                    // crear una solicitud de mantenimiento preventivo en la base de dato de montacarga
                    $equipo = Equipo::find($formularioRegistro->equipo_id);
                    $solicitud = new MontacargaSolicitud();
                    $consecutivo = MontacargaConsecutivo::where('consecutivo_opcion','mantenimiento-preventivo')->first();
                    $next_values_consecutivo = $consecutivo->numero_consecutivo+1;
                    $solicitud->cliente_id = $equipo->cliente_id;
                    $solicitud->tipo_servicio_id = 3; //mantenimiento-preventivo
                    $solicitud->equipo_id = $equipo->id;
                    $solicitud->usuario_creado_id = 1; // crear un app_user debe ser  el usuario actual pero tendriamos que cazarlo con uno de la bd de montacarga
                    $solicitud->usuario_id = 1; //
                    $solicitud->departamento_id =9; // servicio-tecnico
                    $solicitud->horometro = $horometro;
                    $solicitud->estado_id = 1; // abierta
                    $solicitud->descripcion = $obs;
                    $solicitud->consecutivo_exportable = $next_values_consecutivo;

                    if($solicitud->save()){

                        $consecutivo->numero_consecutivo = $next_values_consecutivo;
                        $consecutivo->save();
                        // salvar la copia
                        $copia_sol =new MontacargaCopiaSolicitud();
                        $copia_sol->fill($solicitud->toArray());
                        $copia_sol->usuario_creado_id = 1; // crear un app_user
                        $copia_sol->usuario_id = 1;
                        $copia_sol->nombre_servicio = 'Mantenimiento Preventivo';
                        $copia_sol->nombre_contacto = $equipo->cliente->nombre;
                        $copia_sol->nombre_departamento = 'Servicio técnico';
                        $copia_sol->nombre_estado = 'Abierto';
                        $copia_sol->nombre_usuario_crea = current_user()->getFullName();
                        $copia_sol->equipo = $equipo->numero_parte;
                        $copia_sol->save();
                        // creams el pdf de la solicitud
                        $pdf = $formularioRegistro->savePdf($equipo,$solicitud);


                        MontacargaImagen::create([
                            'name' =>$pdf['url'],
                            'directory'=>'app/public/pdf',
                            'solicitud_id'=>$solicitud->id,
                            'calidad'=>'original',
                            'usuario_id'=>1,
                        ]);

                        $formularioRegistro->solicitud_id = $solicitud->id;
                        $formularioRegistro->nombre_archivo = $pdf['url'];
                        $formularioRegistro->save();
                    }

                }
            }

        }
    }
}
