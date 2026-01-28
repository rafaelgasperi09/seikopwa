<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponenteEquipo extends Model
{


    public function equipo(){
        return $this->belongsTo(Equipo::class);
    }

    public function componete(){
        return $this->belongsTo(Componente::class);
    }
}
