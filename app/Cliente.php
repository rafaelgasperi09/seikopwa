<?php

namespace App;
use App\Zona;
use App\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cliente extends BaseModel
{
    
    protected $table = 'contactos';
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ordenPorDefecto', function (Builder $builder) {
            $builder->orderBy('nombre', 'asc'); // Cambia 'nombre_columna' y 'asc' según necesites
        });
    }

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
                if(str_contains($ruta,'maestros')  )
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

    public function supervisores()
    {
        $supervisores= User::whereRaw("FIND_IN_SET(?, crm_clientes_id)", [$this->id])
            ->whereHas('roles', function ($query) {
                $query->whereIn('slug', ['supervisorc']); // o usa 'slug' si así identificas roles
            })->get()->pluck('fullname','id')->prepend('seleccione','');

        return $supervisores;
    }

    public function operadores()
    {
        $operadores= User::whereRaw("FIND_IN_SET(?, crm_clientes_id)", [$this->id])
            ->whereHas('roles', function ($query) {
                $query->whereIn('slug', ['operadorc']); // o usa 'slug' si así identificas roles
            })->get()->pluck('fullname','id')->prepend('seleccione','');

        return $operadores;
    }
}
