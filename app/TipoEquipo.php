<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TipoEquipo extends BaseModel
{
    protected $table = 'tipo_equipos';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ordenPorDefecto', function (Builder $builder) {
            $builder->orderBy('name', 'asc'); // Cambia 'nombre_columna' y 'asc' según necesites
        });
    }

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }

}
