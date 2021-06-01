<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoMotor extends Model
{
    protected $connection='crm';
    protected $table = 'tipo_motores';

    public function equipos(){
        return $this->hasMany(Equipo::class,'tipo_motore_id');
    }
}
