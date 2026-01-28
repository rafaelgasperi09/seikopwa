<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Marca extends BaseModel
{

    protected $table = 'marcas';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ordenPorDefecto', function (Builder $builder) {
            $builder->orderBy('display_name', 'asc'); // Cambia 'nombre_columna' y 'asc' según necesites
        });
    }

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }
}
