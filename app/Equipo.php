<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
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

    public function ult_horometro(){
        $ult_form = FormularioRegistro::whereEquipoId($this->id)->orderBy('created_at','desc')->first();
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

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltroCliente($query)
    {
        if(current_user()->isCliente()){
            $clientes=explode(',',limpiar_lista(current_user()->crm_clientes_id));
            return $query->whereIn('cliente_id',$clientes); // cliente taller => bodega
        }

        return $query;
    }

}
