<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioSeccion extends BaseModel
{
    use SoftDeletes;
    protected $table = 'formulario_secciones';
    protected $guarded = ['id','typeheadA'];

    public function formulario(){
        return $this->belongsTo(Formulario::class);
    }

    public function campos(){
        return $this->hasMany(FormularioCampo::class);
    }

}
