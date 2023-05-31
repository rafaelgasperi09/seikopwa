<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class EquiposVw extends BaseModel
{

    protected $table = 'equipos_vw';
    protected $fillable=['id','numero_parte','modelo','serie','cliente','updated_at'];


}
