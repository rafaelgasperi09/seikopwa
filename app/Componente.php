<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Componente extends Model
{
    use SoftDeletes;
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
            $clientes=explode(',',limpiar_lista(current_user()->crm_clientes_id));
            return $query->whereIn('cliente_id',$clientes); // cliente taller => bodega
        }
         

        return $query;
    }

    public function ult_horometro(){
        $ult_form = FormularioRegistro::where('componente_id',$this->id)->orderBy('created_at','desc')->first();
        if($ult_form){

           // $campos=\DB::select(DB::Raw(" SELECT id FROM formulario_campos WHERE nombre IN ('lectura_horometro', 'horometro') AND tipo = 'number'
           // AND formulario_campos.deleted_at IS NULL"));
            $campos=FormularioCampo::whereIn('nombre',['lectura_horometro','horometro'])->where('tipo','number')->pluck('id');
            $horometro=$ult_form->data->whereIn('formulario_campo_id',$campos);

            if(count($horometro)>0){
               
                $horometro=$horometro->first()->valor;
            }else{
                $horometro=0;
            }
        }
        else{
            $horometro=0;
        }
        return $horometro;
    }
}
