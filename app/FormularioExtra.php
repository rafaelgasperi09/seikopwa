<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioExtra extends BaseModel
{

    protected $table = 'formulario_extra';
    protected $guarded = ['id'];

}
