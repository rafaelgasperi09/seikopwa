<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MontacargaImagen extends Model
{
    protected $connection='crm';
    protected $table = 'images';
    protected $fillable=['name','directory','equipo_id','componente_id','solicitud_id','calidad','relacion','usuario_id','deleted_at','auto_id'];
}
