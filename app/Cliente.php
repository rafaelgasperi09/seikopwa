<?php

namespace App;
use App\Zona;

use Illuminate\Database\Eloquent\Model;

class Cliente extends BaseModel
{
    
    protected $table = 'contactos';
    protected $guarded = ['id'];


    protected static function booted()
    {

            $eliminados=request()->get('eliminados');
            $estado = 'A';
            if($eliminados=='true'){
                $estado='I';
            }
            if(!empty(request()->get('estado')) and in_array(request()->get('estado'),['A','I']))
                $estado = request()->get('estado');

            $ruta=\Request::route()->getName();
            
            self::addGlobalScope('estado', function ($query) use($estado,$ruta){
                if(!str_contains($ruta,'clientes.update') and !str_contains($ruta,'clientes.edit') )
                    $query->where('contactos.estado',$estado);
            });
    
    }
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
