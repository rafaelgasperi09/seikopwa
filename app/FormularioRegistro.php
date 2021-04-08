<?php

namespace App;

use App\Http\Traits\FilterDataTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioRegistro extends BaseModel
{
    use SoftDeletes;
    protected $table = 'formulario_registro';
    protected $guarded = ['id','typeheadA'];
    protected $creator_field_name = 'creado_por';

    public function creador(){
        return $this->belongsTo('App\User','creado_por');
    }

    public function formulario(){
        return $this->belongsTo(Formulario::class);
    }

    public function data(){
        return $this->hasMany(FormularioData::class);
    }
}
