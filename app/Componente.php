<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    protected $connection='crm';

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id')->withDefault([
            'nombre'=>'N/A'
        ]);
    }

    public function equipos(){
        return $this->hasMany(ComponenteEquipo::class);
    }

    public function formmularioRegistros(){
        return FormularioRegistro::whereComponenteId($this->id)->get();
    }
}
