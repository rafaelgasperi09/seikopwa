<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends BaseModel
{
    protected $connection='crm';
    protected $table = 'marcas';

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }
}
