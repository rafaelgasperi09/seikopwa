<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $connection='mysql';
    protected $table='ubicaciones';
    protected $guarded = ['id'];
}
