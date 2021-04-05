<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo extends BaseModel
{
    protected $connection='crm';
    protected $table = 'equipos';

    public function tipo(){
        return $this->belongsTo(TipoEquipo::class,'tipo_equipos_id');
    }

    public function subTipo(){
        return $this->belongsTo(SubEquipo::class,'sub_equipos_id');
    }

    public function marca(){
        return $this->belongsTo(Marca::class,'marca_id');
    }

}
