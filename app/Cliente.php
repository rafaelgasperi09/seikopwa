<?php

namespace App;
use App\Zona;

use Illuminate\Database\Eloquent\Model;

class Cliente extends BaseModel
{
    
    protected $table = 'contactos';
    protected $guarded = ['id'];

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }

    public function zona(){
        return $this->belongsTo(Zona::class,'zona_id','id')->withDefault([
            'display_name'=>'N/A'
        ]);
    }


    public function getFullNameAttribute() {
        return $this->nombre.' ('.$this->equipos->count().' montacargas)'; //Change the format to whichever you desire
    }
}
