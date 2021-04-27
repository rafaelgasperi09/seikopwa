<?php

namespace App\Observers;

use App\FormularioCampo;
use App\FormularioData;
use App\FormularioRegistro;

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
            $formularioRegistro->save();
        }
    }
}
