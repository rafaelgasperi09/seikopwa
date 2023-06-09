<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends BaseModel
{
    protected $connection='crm';
    protected $table = 'tipo_equipos';

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }

}
