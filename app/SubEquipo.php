<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubEquipo extends BaseModel
{

    protected $table = 'sub_equipos';

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }

}
