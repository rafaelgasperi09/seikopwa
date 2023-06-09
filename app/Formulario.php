<?php

namespace App;

use App\Http\Traits\FilterDataTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formulario extends BaseModel
{
    use SoftDeletes;
    protected $table = 'formularios';
    protected $guarded = ['id'];

    public function creador(){
        return $this->belongsTo('App\User','creado_por');
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function campos(){
        return $this->hasMany(FormularioCampo::class);
    }

    public function registros(){
        return $this->hasMany(FormularioRegistro::class);
    }

    public function data(){
        return $this->hasMany(FormularioData::class);
    }

    public function secciones(){
        return $this->hasMany(FormularioSeccion::class);
    }

    public function notificados(){
        return $this->hasMany(FormularioNotificacion::class);
    }
}
