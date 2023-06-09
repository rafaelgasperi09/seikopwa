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
    public function created(FormularioData $formularioData)
    {
        //$formularioData->user_id = current_user()->id;
        //$formularioData->save();
    }
    /**
     * Handle the formulario data "updated" event.
     *
     * @param  \App\FormularioData  $formularioData
     * @return void
     */
    public function updated(FormularioData $formularioData)
    {
        //$formularioData->user_id = current_user()->id;
        //$formularioData->save();
    }
}
