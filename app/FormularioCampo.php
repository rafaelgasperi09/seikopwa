<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormularioCampo extends BaseModel
{
    protected $table = 'formulario_campos';
    protected $guarded = ['id','typeheadA'];

    public function formulario(){
        return $this->belongsTo(Formulario::class);
    }

    public function secciones(){
        return $this->belongsTo(FormularioSeccion::class);
    }

    public function data(){
        return $this->hasMany(FormularioData::class);
    }
}
