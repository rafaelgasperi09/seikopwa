<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormCargaBateriaView extends Model
{
    protected $connection = 'mysql';
    protected $table = 'form_carga_bateria_view';

    public function componente(){
        return Componente::find($this->componente_id);
    }
}
