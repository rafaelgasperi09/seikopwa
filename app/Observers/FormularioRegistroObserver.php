<?php

namespace App\Observers;

use App\FormularioRegistro;
use App\FormularioRegistroEstatus;

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
        }
    }
}
