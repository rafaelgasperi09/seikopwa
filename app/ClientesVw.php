<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class ClientesVw extends BaseModel
{

    protected $table = 'clientes_vw';
    protected $fillable=['id','nombre','updated_at'];


}
