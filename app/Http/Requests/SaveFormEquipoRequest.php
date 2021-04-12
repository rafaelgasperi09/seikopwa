<?php

namespace App\Http\Requests;

use App\Formulario;
use Illuminate\Foundation\Http\FormRequest;

class SaveFormEquipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $formulario = Formulario::find($this->input('formulario_id'));
        $rules['formulario_id']='required';
        $rules['equipo_id']='required';

        foreach ($formulario->campos()->get() as $campo){

            if($campo->requerido){
                $rules[$campo->nombre]='required';
            }
        }

        switch ($formulario->nombre){
            case 'form_montacarga_daily_check':
                $rules['turno_chequeo_diario']='required';
                break;
        }

        return $rules;
    }
}
