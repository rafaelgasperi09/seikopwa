<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class EquipoStatus extends BaseModel
{
    use SoftDeletes;
    protected $table = 'equipo_status';
    protected $guarded = ['id'];
    protected $fillable=['id','nombre','updated_at'];


}
