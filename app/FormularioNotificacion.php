<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioNotificacion extends BaseModel
{
    use SoftDeletes;
    protected $table = 'formulario_notificaciopnes';
    protected $guarded = ['id','typeheadA'];

    public function formulario(){
        return $this->belongsTo(Formulario::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }
}
