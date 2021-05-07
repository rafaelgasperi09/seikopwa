<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormularioRegistroEstatus extends Model
{
    protected $table='formulario_registro_estatus';
    protected $fillable=['formulario_registro_id','user_id','estatus'];

    public function registro(){
        return $this->belongsTo(FormularioRegistro::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
