<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioData extends BaseModel
{
    use SoftDeletes;
    protected $table = 'formulario_data';
    protected $guarded = ['id','typeheadA'];

    public function registro(){
        return $this->belongsTo(FormularioRegistro::class,'formulario_registro_id');
        
    }

    public function campo(){
        return $this->belongsTo(FormularioCampo::class,'formulario_campo_id');
    }

    public function archivo(){
        return $this->belongsTo(Archivo::class,'archivo_id');
    }

    public function creador(){
        return $this->belongsTo('App\User','user_id');
    }
}
