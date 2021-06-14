<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoMastil extends Model
{
    protected $connection='crm';
    protected $table = 'tipo_mastils';

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }
}
