<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormularioSeccion extends BaseModel
{
    protected $table = 'formulario_secciones';
    protected $guarded = ['id','typeheadA'];

    public function formulario(){
        return $this->belongsTo(Formulario::class);
    }

    public function campos(){
        return $this->hasMany(FormularioCampo::class);
    }

}
