<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends BaseModel
{
    use SoftDeletes;
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

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id')->withDefault([
            'nombre'=>'N/A'
        ]);
    }
}
