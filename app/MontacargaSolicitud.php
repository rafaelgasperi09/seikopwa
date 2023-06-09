<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MontacargaSolicitud extends Model
{
    protected $connection='crm';
    protected $table='solicitudes';
    protected $fillable=['id','cliente_id','tipo_servicio_id','equipo_id','usuario_id','departamento_id','horometro','estado_id','descripcion','consecutivo_exportable'];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function equipo(){
        return $this->belongsTo(Equipo::class);
    }
}
