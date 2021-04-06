<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormularioNotificacion extends BaseModel
{
    protected $table = 'formulario_notificaciopnes';
    protected $guarded = ['id','typeheadA'];

    public function formulario(){
        return $this->belongsTo(Formulario::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }
}
