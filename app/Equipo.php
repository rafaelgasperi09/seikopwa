<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends BaseModel
{
    use SoftDeletes;
    protected $connection='crm';
    protected $table = 'equipos';

    public function tipo(){
        return $this->belongsTo(TipoEquipo::class,'tipo_equipos_id')->withDefault([
            'display_name'=>'N/A',
            'name'=>'N/A'
        ]);;
    }

    public function subTipo(){
        return $this->belongsTo(SubEquipo::class,'sub_equipos_id');
    }

    public function marca(){
        return $this->belongsTo(Marca::class,'marca_id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class,'estado_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id')->withDefault([
            'nombre'=>'N/A'
        ]);
    }

    public function motor(){
        return $this->belongsTo(TipoMotor::class,'tipo_motore_id')->withDefault([
            'display_name'=>'N/A'
        ]);
    }

    public function mastile(){
        return $this->belongsTo(TipoMastil::class,'tipo_mastil_id')->withDefault([
            'nombre'=>'N/A'
        ]);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltroCliente($query)
    {
        if(current_user()->isCliente()){
            $clientes=explode(',',current_user()->crm_clientes_id);
            return $query->whereIn('cliente_id',$clientes); // cliente taller => bodega
        }

        return $query;
    }

}
