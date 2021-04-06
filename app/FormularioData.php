<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormularioData extends BaseModel
{
    protected $table = 'formulario_data';
    protected $guarded = ['id','typeheadA'];

    public function registro(){
        return $this->belongsTo(FormularioRegistro::class);
    }

    public function campo(){
        return $this->belongsTo(FormularioCampo::class,'formulario_campo_id');
    }

    public function archivo(){
        return $this->belongsTo(Archivo::class,'archivo_id');
    }
}
