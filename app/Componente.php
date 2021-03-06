<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    protected $connection='crm';

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id')->withDefault([
            'nombre'=>'N/A'
        ]);
    }

    public function equipos(){
        return $this->hasMany(ComponenteEquipo::class);
    }

    public function formmularioRegistros(){
        return FormularioRegistro::whereComponenteId($this->id)->orderBy('created_at','DESC')->take(5000)->get();
    }

    public function historialCargas(){
        return $this->hasMany(FormCargaBateriaView::class,'componente_id');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltroBodega($query)
    {
        if(!empty(current_user()->crm_clientes_id)){
            $clientes=explode(',',current_user()->crm_clientes_id);
            return $query->whereIn('cliente_id',$clientes); // cliente taller => bodega
        }
         

        return $query;
    }
}
