<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MontacargaCopiaSolicitud extends Model
{
    protected $connection='crm';
    protected $table='copia_solicitudes';
    protected $primaryKey='copia_solicitud_id';
    protected $fillable=['id','cliente_id','tipo_servicio_id','equipo_id','usuario_id','departamento_id','horometro','estado_id','descripcion','consecutivo_exportable'];
}
