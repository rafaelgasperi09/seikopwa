<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormularioRegistroEstatus extends Model
{
    protected $table='formulario_registro_estatus';
    protected $fillable=['formulario_registro_id','user_id','estatus'];

    public function registro(){
        return $this->belongsTo(FormularioRegistro::class,'formulario_registro_id')->with('tecnicoAsignado');
    }

    public function user(){
        return $this->belongsTo(User::class)->withDefault([
            'first_name'=>'',
            'last_name'=>''
        ]);
    }

    public function files(){
       // return $this->hasMany(File::class)->where
    }
}
