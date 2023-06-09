<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends BaseModel
{
    protected $connection='crm';
    protected $table = 'contactos';

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }

    public function getFullNameAttribute() {
        return $this->nombre.' ('.$this->equipos->count().' montacargas)'; //Change the format to whichever you desire
    }
}
